-- =====================================================================
-- Services Marketplace Schema (PostgreSQL 15+)
-- Customers <-> Professionals (plumber, beautician, doctor, engineer, labour...)
-- =====================================================================
CREATE EXTENSION IF NOT EXISTS postgis;
CREATE EXTENSION IF NOT EXISTS pgcrypto;

-- ---------- ENUMS ----------
CREATE TYPE user_role        AS ENUM ('customer','provider','admin','support');
CREATE TYPE user_status      AS ENUM ('active','suspended','deleted');
CREATE TYPE provider_kind    AS ENUM ('individual','agency');
CREATE TYPE verification     AS ENUM ('pending','in_review','approved','rejected','expired');
CREATE TYPE pricing_model    AS ENUM ('fixed','hourly','per_unit','quote');   -- quote = site visit / custom
CREATE TYPE service_mode     AS ENUM ('at_customer','at_provider','online');  -- doctor can be online
CREATE TYPE booking_status   AS ENUM ('created','searching','assigned','accepted','en_route',
                                      'in_progress','completed','cancelled','failed');
CREATE TYPE assignment_status AS ENUM ('offered','accepted','rejected','expired','cancelled');
CREATE TYPE payment_status   AS ENUM ('pending','authorized','captured','failed','refunded','partially_refunded');
CREATE TYPE payment_method   AS ENUM ('upi','card','netbanking','wallet','cash');
CREATE TYPE ledger_type      AS ENUM ('earning','commission','penalty','bonus','payout','refund_adjustment','cash_collected');
CREATE TYPE quote_status     AS ENUM ('requested','quoted','accepted','rejected','expired');

-- ---------- IDENTITY ----------
CREATE TABLE users (
  id            UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  role          user_role   NOT NULL,
  full_name     TEXT        NOT NULL,
  phone         TEXT UNIQUE NOT NULL,
  email         TEXT UNIQUE,
  avatar_url    TEXT,
  language      TEXT DEFAULT 'en',
  status        user_status NOT NULL DEFAULT 'active',
  created_at    TIMESTAMPTZ NOT NULL DEFAULT now(),
  updated_at    TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE TABLE addresses (
  id          UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  user_id     UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  label       TEXT,                       -- Home, Office
  line1       TEXT NOT NULL,
  line2       TEXT,
  landmark    TEXT,
  city_id     INT  NOT NULL,
  pincode     TEXT NOT NULL,
  location    GEOGRAPHY(Point,4326) NOT NULL,
  is_default  BOOLEAN DEFAULT false
);
CREATE INDEX idx_addresses_user ON addresses(user_id);
CREATE INDEX idx_addresses_geo  ON addresses USING GIST(location);

CREATE TABLE cities (
  id        SERIAL PRIMARY KEY,
  name      TEXT NOT NULL,
  state     TEXT NOT NULL,
  country   TEXT NOT NULL DEFAULT 'IN',
  timezone  TEXT NOT NULL DEFAULT 'Asia/Kolkata',
  is_active BOOLEAN DEFAULT true
);
ALTER TABLE addresses ADD FOREIGN KEY (city_id) REFERENCES cities(id);

-- ---------- CATALOG ----------
CREATE TABLE categories (                  -- hierarchical: Home Repair > Plumbing
  id         SERIAL PRIMARY KEY,
  parent_id  INT REFERENCES categories(id),
  name       TEXT NOT NULL,
  slug       TEXT UNIQUE NOT NULL,
  icon_url   TEXT,
  requires_license BOOLEAN DEFAULT false,  -- doctors, electricians, engineers
  sort_order INT DEFAULT 0,
  is_active  BOOLEAN DEFAULT true
);

CREATE TABLE services (
  id            SERIAL PRIMARY KEY,
  category_id   INT NOT NULL REFERENCES categories(id),
  name          TEXT NOT NULL,
  description   TEXT,
  pricing_model pricing_model NOT NULL,
  mode          service_mode  NOT NULL DEFAULT 'at_customer',
  duration_min  INT,                       -- estimated
  min_provider_experience_months INT DEFAULT 0,
  attributes    JSONB DEFAULT '{}',        -- category-specific fields (e.g. AC type, room size)
  is_active     BOOLEAN DEFAULT true
);
CREATE INDEX idx_services_category ON services(category_id);

CREATE TABLE city_service_prices (         -- price varies by city
  service_id  INT REFERENCES services(id),
  city_id     INT REFERENCES cities(id),
  base_price  NUMERIC(10,2) NOT NULL,
  unit_label  TEXT,                        -- 'hour', 'sq ft', 'visit'
  platform_commission_pct NUMERIC(5,2) NOT NULL DEFAULT 20,
  PRIMARY KEY (service_id, city_id)
);

CREATE TABLE service_addons (
  id          SERIAL PRIMARY KEY,
  service_id  INT NOT NULL REFERENCES services(id),
  name        TEXT NOT NULL,
  price       NUMERIC(10,2) NOT NULL
);

-- ---------- PROVIDERS ----------
CREATE TABLE providers (
  user_id        UUID PRIMARY KEY REFERENCES users(id),
  kind           provider_kind NOT NULL DEFAULT 'individual',
  agency_id      UUID REFERENCES providers(user_id),       -- worker belongs to an agency
  display_name   TEXT NOT NULL,
  bio            TEXT,
  experience_months INT DEFAULT 0,
  verification   verification NOT NULL DEFAULT 'pending',
  is_online      BOOLEAN DEFAULT false,
  last_location  GEOGRAPHY(Point,4326),
  last_seen_at   TIMESTAMPTZ,
  avg_rating     NUMERIC(3,2) DEFAULT 0,
  rating_count   INT DEFAULT 0,
  jobs_completed INT DEFAULT 0,
  acceptance_rate NUMERIC(5,2) DEFAULT 100,
  onboarded_at   TIMESTAMPTZ
);
CREATE INDEX idx_providers_geo ON providers USING GIST(last_location);

CREATE TABLE provider_documents (          -- KYC + licences + police verification
  id           UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  provider_id  UUID NOT NULL REFERENCES providers(user_id) ON DELETE CASCADE,
  doc_type     TEXT NOT NULL,              -- aadhaar, pan, medical_registration, electrician_license, police_verification
  doc_number   TEXT,
  file_url     TEXT NOT NULL,
  issued_by    TEXT,
  expires_on   DATE,
  status       verification NOT NULL DEFAULT 'pending',
  reviewed_by  UUID REFERENCES users(id),
  reviewed_at  TIMESTAMPTZ
);

CREATE TABLE provider_services (           -- what each provider can do
  provider_id   UUID REFERENCES providers(user_id) ON DELETE CASCADE,
  service_id    INT  REFERENCES services(id),
  custom_price  NUMERIC(10,2),             -- allowed for quote / hourly models
  is_enabled    BOOLEAN DEFAULT true,
  PRIMARY KEY (provider_id, service_id)
);

CREATE TABLE provider_service_areas (
  id          SERIAL PRIMARY KEY,
  provider_id UUID NOT NULL REFERENCES providers(user_id) ON DELETE CASCADE,
  city_id     INT  NOT NULL REFERENCES cities(id),
  center      GEOGRAPHY(Point,4326),
  radius_km   NUMERIC(5,2) DEFAULT 10
);

CREATE TABLE provider_availability (       -- weekly recurring working hours
  id          SERIAL PRIMARY KEY,
  provider_id UUID NOT NULL REFERENCES providers(user_id) ON DELETE CASCADE,
  weekday     SMALLINT CHECK (weekday BETWEEN 0 AND 6),
  start_time  TIME NOT NULL,
  end_time    TIME NOT NULL
);

CREATE TABLE provider_time_off (
  id          SERIAL PRIMARY KEY,
  provider_id UUID NOT NULL REFERENCES providers(user_id) ON DELETE CASCADE,
  starts_at   TIMESTAMPTZ NOT NULL,
  ends_at     TIMESTAMPTZ NOT NULL,
  reason      TEXT
);

-- ---------- QUOTES (plumber/engineer/labour: price after inspection) ----------
CREATE TABLE quotes (
  id           UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  customer_id  UUID NOT NULL REFERENCES users(id),
  service_id   INT  NOT NULL REFERENCES services(id),
  address_id   UUID NOT NULL REFERENCES addresses(id),
  description  TEXT,
  media_urls   TEXT[],
  status       quote_status NOT NULL DEFAULT 'requested',
  created_at   TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE quote_offers (
  id           UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  quote_id     UUID NOT NULL REFERENCES quotes(id) ON DELETE CASCADE,
  provider_id  UUID NOT NULL REFERENCES providers(user_id),
  amount       NUMERIC(10,2) NOT NULL,
  eta_hours    INT,
  note         TEXT,
  is_accepted  BOOLEAN DEFAULT false,
  created_at   TIMESTAMPTZ DEFAULT now()
);

-- ---------- BOOKINGS ----------
CREATE TABLE bookings (
  id              UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  customer_id     UUID NOT NULL REFERENCES users(id),
  provider_id     UUID REFERENCES providers(user_id),      -- final assigned provider
  address_id      UUID REFERENCES addresses(id),           -- null for online mode
  quote_offer_id  UUID REFERENCES quote_offers(id),
  mode            service_mode NOT NULL,
  status          booking_status NOT NULL DEFAULT 'created',
  scheduled_start TIMESTAMPTZ NOT NULL,
  scheduled_end   TIMESTAMPTZ,
  actual_start    TIMESTAMPTZ,
  actual_end      TIMESTAMPTZ,
  subtotal        NUMERIC(10,2) NOT NULL,
  discount        NUMERIC(10,2) DEFAULT 0,
  tax             NUMERIC(10,2) DEFAULT 0,
  total           NUMERIC(10,2) NOT NULL,
  completion_otp  TEXT,                                    -- start/finish verification
  customer_notes  TEXT,
  cancelled_by    UUID REFERENCES users(id),
  cancel_reason   TEXT,
  created_at      TIMESTAMPTZ NOT NULL DEFAULT now()
);
CREATE INDEX idx_bookings_customer ON bookings(customer_id, created_at DESC);
CREATE INDEX idx_bookings_provider ON bookings(provider_id, scheduled_start);
CREATE INDEX idx_bookings_status   ON bookings(status);

CREATE TABLE booking_items (
  id          UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  booking_id  UUID NOT NULL REFERENCES bookings(id) ON DELETE CASCADE,
  service_id  INT  NOT NULL REFERENCES services(id),
  addon_id    INT  REFERENCES service_addons(id),
  quantity    NUMERIC(8,2) NOT NULL DEFAULT 1,
  unit_price  NUMERIC(10,2) NOT NULL,                      -- snapshot of price at booking time
  line_total  NUMERIC(10,2) NOT NULL
);

CREATE TABLE booking_assignments (         -- dispatch attempts (offer -> accept/reject)
  id           UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  booking_id   UUID NOT NULL REFERENCES bookings(id) ON DELETE CASCADE,
  provider_id  UUID NOT NULL REFERENCES providers(user_id),
  status       assignment_status NOT NULL DEFAULT 'offered',
  distance_km  NUMERIC(6,2),
  offered_at   TIMESTAMPTZ DEFAULT now(),
  responded_at TIMESTAMPTZ,
  expires_at   TIMESTAMPTZ
);
CREATE INDEX idx_assign_provider ON booking_assignments(provider_id, status);

CREATE TABLE booking_status_history (
  id          BIGSERIAL PRIMARY KEY,
  booking_id  UUID NOT NULL REFERENCES bookings(id) ON DELETE CASCADE,
  from_status booking_status,
  to_status   booking_status NOT NULL,
  changed_by  UUID REFERENCES users(id),
  changed_at  TIMESTAMPTZ DEFAULT now(),
  meta        JSONB
);

-- ---------- PAYMENTS & MONEY ----------
CREATE TABLE payments (
  id              UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  booking_id      UUID NOT NULL REFERENCES bookings(id),
  method          payment_method NOT NULL,
  status          payment_status NOT NULL DEFAULT 'pending',
  amount          NUMERIC(10,2) NOT NULL,
  gateway         TEXT,                                    -- razorpay, stripe
  gateway_txn_id  TEXT,
  idempotency_key TEXT UNIQUE,
  created_at      TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE refunds (
  id          UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  payment_id  UUID NOT NULL REFERENCES payments(id),
  amount      NUMERIC(10,2) NOT NULL,
  reason      TEXT,
  gateway_refund_id TEXT,
  created_at  TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE provider_ledger (             -- double-entry style, never update, only append
  id          BIGSERIAL PRIMARY KEY,
  provider_id UUID NOT NULL REFERENCES providers(user_id),
  booking_id  UUID REFERENCES bookings(id),
  type        ledger_type NOT NULL,
  amount      NUMERIC(10,2) NOT NULL,                      -- +credit / -debit
  created_at  TIMESTAMPTZ DEFAULT now()
);
CREATE INDEX idx_ledger_provider ON provider_ledger(provider_id, created_at DESC);

CREATE TABLE payouts (
  id            UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  provider_id   UUID NOT NULL REFERENCES providers(user_id),
  amount        NUMERIC(10,2) NOT NULL,
  bank_ref      TEXT,
  status        TEXT NOT NULL DEFAULT 'pending',
  period_start  DATE, period_end DATE,
  paid_at       TIMESTAMPTZ
);

CREATE TABLE provider_bank_accounts (
  id            UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  provider_id   UUID NOT NULL REFERENCES providers(user_id),
  account_name  TEXT, account_no_enc BYTEA, ifsc TEXT, upi_id TEXT,
  is_primary    BOOLEAN DEFAULT true
);

-- ---------- TRUST: REVIEWS, DISPUTES ----------
CREATE TABLE reviews (
  id          UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  booking_id  UUID UNIQUE NOT NULL REFERENCES bookings(id),
  customer_id UUID NOT NULL REFERENCES users(id),
  provider_id UUID NOT NULL REFERENCES providers(user_id),
  rating      SMALLINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
  comment     TEXT,
  tags        TEXT[],                                      -- punctual, polite, neat
  created_at  TIMESTAMPTZ DEFAULT now()
);
CREATE INDEX idx_reviews_provider ON reviews(provider_id);

CREATE TABLE provider_reviews_of_customer (  -- two-way rating
  booking_id  UUID PRIMARY KEY REFERENCES bookings(id),
  rating      SMALLINT CHECK (rating BETWEEN 1 AND 5),
  comment     TEXT
);

CREATE TABLE support_tickets (
  id          UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  booking_id  UUID REFERENCES bookings(id),
  raised_by   UUID NOT NULL REFERENCES users(id),
  category    TEXT NOT NULL,                               -- no_show, damage, overcharge, safety
  description TEXT,
  status      TEXT NOT NULL DEFAULT 'open',
  assigned_to UUID REFERENCES users(id),
  created_at  TIMESTAMPTZ DEFAULT now(),
  resolved_at TIMESTAMPTZ
);

-- ---------- GROWTH ----------
CREATE TABLE coupons (
  id          SERIAL PRIMARY KEY,
  code        TEXT UNIQUE NOT NULL,
  discount_pct NUMERIC(5,2), discount_flat NUMERIC(10,2),
  max_discount NUMERIC(10,2),
  min_order   NUMERIC(10,2) DEFAULT 0,
  valid_from  TIMESTAMPTZ, valid_to TIMESTAMPTZ,
  usage_limit INT, per_user_limit INT DEFAULT 1,
  category_id INT REFERENCES categories(id)
);

CREATE TABLE coupon_redemptions (
  id         BIGSERIAL PRIMARY KEY,
  coupon_id  INT NOT NULL REFERENCES coupons(id),
  user_id    UUID NOT NULL REFERENCES users(id),
  booking_id UUID NOT NULL REFERENCES bookings(id),
  redeemed_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE notifications (
  id         BIGSERIAL PRIMARY KEY,
  user_id    UUID NOT NULL REFERENCES users(id),
  channel    TEXT NOT NULL,                                -- push, sms, whatsapp, email
  title      TEXT, body TEXT,
  data       JSONB,
  sent_at    TIMESTAMPTZ, read_at TIMESTAMPTZ
);

CREATE TABLE chat_messages (                 -- customer <-> provider, scoped to a booking
  id         BIGSERIAL PRIMARY KEY,
  booking_id UUID NOT NULL REFERENCES bookings(id),
  sender_id  UUID NOT NULL REFERENCES users(id),
  body       TEXT, media_url TEXT,
  created_at TIMESTAMPTZ DEFAULT now()
);

-- Audit trail for admin actions
CREATE TABLE audit_logs (
  id         BIGSERIAL PRIMARY KEY,
  actor_id   UUID REFERENCES users(id),
  action     TEXT NOT NULL,
  entity     TEXT, entity_id TEXT,
  diff       JSONB,
  created_at TIMESTAMPTZ DEFAULT now()
);
