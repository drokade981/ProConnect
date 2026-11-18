review urban company. I want to create same platform which connect customer to professionals like labour, beauty specialist doctor, engineer, plumber and all those who provide any kind of service. create a database schema to manage all those and create high level design for it

I'll put the schema in a SQL file, since it's long, and cover the review and architecture below.

# 1. Urban Company review

I wrote this from what I know, so check current figures yourself, since the company has grown quickly.

**What it does well**
- **Standardised services.** Fixed prices, fixed service menus, and a defined time slot. Customers aren't bargaining or guessing the price.
- **Trust layer.** Background checks, trained professionals, uniforms, kits and products, OTP-based start and finish, and ratings in both directions.
- **Managed quality.** It controls training, products and replacements instead of acting as a plain listing site. This is the main reason people pay a premium over a local contact.
- **Dense, city-wise operations.** It focuses on a few categories per city, which keeps professional utilisation high.
- **Repeat usage.** Salon and cleaning services are frequent, so lifetime value is good.

**Weaknesses and gaps you can exploit**
- **Provider complaints.** Commission is high (about 20–30%), and professionals often complain about rating-based penalties, rejected leads and pricing pressure.
- **Customer complaints.** Late arrivals or cancellations, upselling during the visit, and price differences between cities.
- **Weak at custom work.** Plumbing, electrical, carpentry and construction need an inspection before pricing. Fixed menus handle these poorly.
- **Little coverage of licensed professions** such as doctors and engineers, and of daily-wage labour. These are different models (licence verification, online consultation, hourly or daily rates).
- **Tier-2 and tier-3 towns** are under-served. This matters for a city like Jabalpur.

**Takeaway:** Don't try to copy everything. Start with one city and 3–4 categories, such as plumber, electrician, beautician and cleaning, and win on supply quality. Add doctors and engineers later, because licensed professions need legal and verification work.

# 2. Database schema

The full PostgreSQL schema is in `services_marketplace_schema.sql`. It has about 35 tables and uses PostGIS for location. The main design decisions are:

- **Generic catalog:** `categories` (hierarchical) → `services` → `city_service_prices`. Adding a new profession means adding rows, not changing the schema.
- **`pricing_model` enum (fixed, hourly, per_unit, quote):** this handles beauticians (fixed) and labour (hourly or daily). Plumbers and engineers use the `quotes` and `quote_offers` flow, where several providers bid after seeing photos.
- **`service_mode` (at_customer, at_provider, online):** a doctor can consult online, while a plumber always goes to the customer's address.
- **Licensing:** `categories.requires_license` plus `provider_documents` (with an expiry date) enforce checks such as medical registration before a provider can go live.
- **Dispatch:** `booking_assignments` records every offer, accept and reject. This gives you acceptance-rate data and a fair rotation.
- **Snapshots:** `booking_items.unit_price` stores the price at booking time, so later price changes don't affect old bookings.
- **Money:** `payments` and `refunds` are separate. `provider_ledger` is append-only, so balances are always auditable, and `payouts` settles from it.
- **Trust:** two-way reviews, support tickets, chat tied to a booking, and `audit_logs` for admin actions.

# 3. High-level design

**Clients**
- Customer app (Android, iOS, web)
- Provider app (job offers, schedule, earnings, KYC)
- Admin and ops panel (verification, dispatch overrides, disputes, pricing)

**Backend services** (start as a modular monolith and split later)

| Service | Responsibility |
|---|---|
| Auth/User | OTP login (phone-first), roles, profiles |
| Catalog | Categories, services, city pricing, search |
| Provider | Onboarding, KYC, documents, availability, ratings |
| Booking | Booking lifecycle and state machine, cancellations, rescheduling |
| Dispatch/Matching | Finds nearby, qualified, free providers and sends offers |
| Quote | Custom-quote flow for inspection-based services |
| Payment/Wallet | Gateway integration, refunds, ledger, payouts, commission |
| Notification | Push, SMS and WhatsApp |
| Chat/Call | Masked calling so phone numbers stay private |
| Review/Trust | Ratings, fraud and abuse detection |
| Support | Tickets and disputes |

**Core flow: instant booking**
1. The customer picks a service, address and slot, and the booking is created with the status `searching`.
2. Dispatch queries providers using a PostGIS or Redis GEO radius search. It filters by skill, availability, verification and rating, then ranks them by distance, rating and acceptance rate.
3. It sends offers to the top N providers, with a 30–60 second expiry.
4. The first provider to accept wins, using an atomic update or Redis lock to prevent double assignment. The others are told it's gone.
5. The status then moves through `en_route` and `in_progress` to `completed`, with OTP at start and finish.
6. Payment is captured, the commission is deducted, and the provider's ledger is credited.
7. Both sides leave reviews.

**Tech stack suggestion**
- **Backend:** Node.js (NestJS) or Java/Spring Boot. Go works for the dispatch service.
- **Database:** PostgreSQL + PostGIS as the main store. Redis for caching, provider location, locks and rate limits.
- **Search:** Elasticsearch or OpenSearch for service search (Postgres full-text search is enough at first).
- **Async:** Kafka or RabbitMQ for events like `BookingCreated`, `ProviderAssigned` and `PaymentCaptured`.
- **Real-time:** WebSocket or Firebase for live tracking and job offers.
- **Storage:** S3 for KYC documents, job photos and media.
- **Infra:** Docker + Kubernetes, CI/CD, and Prometheus/Grafana plus Sentry for monitoring.
- **Mobile:** Flutter or React Native, which lets one team build both apps.
- **Payments:** Razorpay or Cashfree for UPI, cards and payouts.

**Non-functional points**
- Index on `(status)` and geo columns, and partition `bookings` and `notifications` by month as they grow.
- Idempotency keys on payments to prevent double charges.
- Encrypt PII and bank details, and keep an audit trail for admin actions.
- Privacy: masked calls, and data retention rules for KYC under India's DPDP Act.
- Rate limiting and OTP abuse protection.

# 4. Build order

1. **MVP (2–3 months):** one city, 3–4 categories, customer and provider apps, manual provider onboarding, simple dispatch, UPI plus cash, and ratings.
2. **Phase 2:** automated matching, the quote flow, wallet and payouts, coupons, and a better admin panel.
3. **Phase 3:** doctors and online consultation (needs a legal and licensing review), agencies and teams, subscriptions, and more cities.

Supply quality is the hard part, more than the technology. Plan your provider recruitment, training and background checks early.

If you'd like, I can next draw an ER diagram, write the REST API list, or design the dispatch algorithm in detail.