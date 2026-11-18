<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Category;
use App\Models\City;
use App\Models\CityServicePrice;
use App\Models\Payment;
use App\Models\Provider;
use App\Models\ProviderDocument;
use App\Models\ProviderService;
use App\Models\Service;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with demo marketplace data.
     */
    public function run(): void
    {
        // 1. Seed Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@urbanapp.com'],
            [
                'full_name' => 'Super Admin',
                'phone' => '+919999000001',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        // 2. Seed Customer Users
        $customer1 = User::firstOrCreate(
            ['email' => 'rahul.sharma@example.com'],
            [
                'full_name' => 'Rahul Sharma',
                'phone' => '+919876543210',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'status' => 'active',
            ]
        );

        $customer2 = User::firstOrCreate(
            ['email' => 'priya.patel@example.com'],
            [
                'full_name' => 'Priya Patel',
                'phone' => '+919876543211',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'status' => 'active',
            ]
        );

        // 3. Seed Cities
        $mumbai = City::firstOrCreate(['name' => 'Mumbai'], ['state' => 'Maharashtra', 'country' => 'IN']);
        $bengaluru = City::firstOrCreate(['name' => 'Bengaluru'], ['state' => 'Karnataka', 'country' => 'IN']);
        $delhi = City::firstOrCreate(['name' => 'Delhi NCR'], ['state' => 'Delhi', 'country' => 'IN']);
        $jabalpur = City::firstOrCreate(['name' => 'Jabalpur'], ['state' => 'Madhya Pradesh', 'country' => 'IN']);

        // 4. Seed Addresses for Customers
        $addr1 = Address::where('user_id', $customer1->id)->where('label', 'Home')->first();
        if (!$addr1) {
            $addr1 = new Address();
            $addr1->user_id = $customer1->id;
            $addr1->label = 'Home';
            $addr1->line1 = 'Flat 402, Sunshine Heights, Powai';
            $addr1->city_id = $mumbai->id;
            $addr1->pincode = '400076';
            $addr1->location = DB::raw("ST_SRID(Point(72.9054, 19.1176), 4326)");
            $addr1->is_default = true;
            $addr1->save();
        }

        // 5. Seed Service Categories
        $plumbingCat = Category::firstOrCreate(['slug' => 'plumbing'], [
            'name' => 'Plumbing Services',
            'requires_license' => false,
            'sort_order' => 1,
        ]);

        $electricCat = Category::firstOrCreate(['slug' => 'electrical'], [
            'name' => 'Electrician & Wiring',
            'requires_license' => true,
            'sort_order' => 2,
        ]);

        $beautyCat = Category::firstOrCreate(['slug' => 'beauty-wellness'], [
            'name' => 'Salon & Beauty Specialists',
            'requires_license' => false,
            'sort_order' => 3,
        ]);

        $cleaningCat = Category::firstOrCreate(['slug' => 'cleaning'], [
            'name' => 'Home Deep Cleaning',
            'requires_license' => false,
            'sort_order' => 4,
        ]);

        $medicalCat = Category::firstOrCreate(['slug' => 'medical-consult'], [
            'name' => 'Medical & Doctor Consultation',
            'requires_license' => true,
            'sort_order' => 5,
        ]);

        // 6. Seed Services
        $acRepair = Service::firstOrCreate(['name' => 'AC Service & Deep Cleaning'], [
            'category_id' => $electricCat->id,
            'description' => 'Comprehensive jet-pump coil cleaning and gas checkup',
            'pricing_model' => 'fixed',
            'mode' => 'at_customer',
            'duration_min' => 90,
            'is_active' => true,
        ]);

        $leakRepair = Service::firstOrCreate(['name' => 'Tap & Pipe Leakage Repair'], [
            'category_id' => $plumbingCat->id,
            'description' => 'Fix leaking taps, pipes, flush tanks and washbasin fittings',
            'pricing_model' => 'fixed',
            'mode' => 'at_customer',
            'duration_min' => 45,
            'is_active' => true,
        ]);

        $facialSpa = Service::firstOrCreate(['name' => 'Hydra Facial & Glow Treatment'], [
            'category_id' => $beautyCat->id,
            'description' => 'Premium skin hydration treatment with herbal products',
            'pricing_model' => 'fixed',
            'mode' => 'at_customer',
            'duration_min' => 60,
            'is_active' => true,
        ]);

        $teleDoc = Service::firstOrCreate(['name' => 'Online General Physician Consult'], [
            'category_id' => $medicalCat->id,
            'description' => 'Video consultation with licensed MBBS general practitioner',
            'pricing_model' => 'fixed',
            'mode' => 'online',
            'duration_min' => 20,
            'is_active' => true,
        ]);

        // City prices
        CityServicePrice::firstOrCreate(
            ['service_id' => $acRepair->id, 'city_id' => $mumbai->id],
            ['base_price' => 799.00, 'platform_commission_pct' => 20.00]
        );
        CityServicePrice::firstOrCreate(
            ['service_id' => $leakRepair->id, 'city_id' => $mumbai->id],
            ['base_price' => 349.00, 'platform_commission_pct' => 20.00]
        );
        CityServicePrice::firstOrCreate(
            ['service_id' => $facialSpa->id, 'city_id' => $mumbai->id],
            ['base_price' => 1299.00, 'platform_commission_pct' => 18.00]
        );

        // 7. Seed Providers
        // Provider 1: Approved Electrician
        $userPro1 = User::firstOrCreate(['email' => 'vikram.pro@example.com'], [
            'full_name' => 'Vikram Verma',
            'phone' => '+919111222333',
            'password' => Hash::make('password'),
            'role' => 'provider',
            'status' => 'active',
        ]);

        $pro1 = Provider::firstOrCreate(['user_id' => $userPro1->id], [
            'display_name' => 'Vikram Electricals',
            'bio' => 'Certified wireman with 8+ years experience in domestic & commercial setups.',
            'experience_months' => 96,
            'verification' => 'approved',
            'is_online' => true,
            'avg_rating' => 4.9,
            'rating_count' => 142,
            'jobs_completed' => 138,
            'acceptance_rate' => 98.00,
            'onboarded_at' => now()->subMonths(6),
        ]);

        ProviderService::firstOrCreate([
            'provider_id' => $pro1->user_id,
            'service_id' => $acRepair->id,
        ]);

        ProviderDocument::firstOrCreate(['provider_id' => $pro1->user_id, 'doc_type' => 'aadhaar'], [
            'doc_number' => 'XXXX-XXXX-4589',
            'file_url' => 'https://placehold.co/800x600/png?text=Aadhaar+Verified',
            'status' => 'approved',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
        ]);

        ProviderDocument::firstOrCreate(['provider_id' => $pro1->user_id, 'doc_type' => 'electrician_license'], [
            'doc_number' => 'EL-MH-2018-9921',
            'file_url' => 'https://placehold.co/800x600/png?text=Electrical+License',
            'issued_by' => 'Maharashtra State Electricity Board',
            'status' => 'approved',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
        ]);

        // Provider 2: Pending Doctor Verification
        $userPro2 = User::firstOrCreate(['email' => 'dr.ananya@example.com'], [
            'full_name' => 'Dr. Ananya Sen',
            'phone' => '+919444555666',
            'password' => Hash::make('password'),
            'role' => 'provider',
            'status' => 'active',
        ]);

        $pro2 = Provider::firstOrCreate(['user_id' => $userPro2->id], [
            'display_name' => 'Dr. Ananya Sen (MBBS)',
            'bio' => 'General Physician, 5 years OPD experience at Apollo Hospitals.',
            'experience_months' => 60,
            'verification' => 'pending',
            'is_online' => false,
            'avg_rating' => 5.0,
            'rating_count' => 0,
            'jobs_completed' => 0,
            'acceptance_rate' => 100.00,
        ]);

        ProviderDocument::firstOrCreate(['provider_id' => $pro2->user_id, 'doc_type' => 'medical_registration'], [
            'doc_number' => 'MCI-REG-2021-8841',
            'file_url' => 'https://placehold.co/800x600/png?text=Medical+Council+Certificate',
            'issued_by' => 'Medical Council of India',
            'expires_on' => now()->addYears(3),
            'status' => 'pending',
        ]);

        ProviderDocument::firstOrCreate(['provider_id' => $pro2->user_id, 'doc_type' => 'pan'], [
            'doc_number' => 'ABCPS1234F',
            'file_url' => 'https://placehold.co/800x600/png?text=PAN+Card',
            'status' => 'pending',
        ]);

        // 8. Seed Bookings
        $booking1 = Booking::firstOrCreate(['customer_id' => $customer1->id, 'total' => 799.00], [
            'provider_id' => $pro1->user_id,
            'address_id' => $addr1->id,
            'mode' => 'at_customer',
            'status' => 'completed',
            'scheduled_start' => now()->subDays(2)->setHour(10)->setMinute(0),
            'scheduled_end' => now()->subDays(2)->setHour(11)->setMinute(30),
            'actual_start' => now()->subDays(2)->setHour(10)->setMinute(5),
            'actual_end' => now()->subDays(2)->setHour(11)->setMinute(25),
            'subtotal' => 799.00,
            'discount' => 0,
            'tax' => 0,
            'completion_otp' => '4821',
            'customer_notes' => 'Please bring ladder if possible.',
        ]);

        BookingItem::firstOrCreate(['booking_id' => $booking1->id, 'service_id' => $acRepair->id], [
            'quantity' => 1,
            'unit_price' => 799.00,
            'line_total' => 799.00,
        ]);

        Payment::firstOrCreate(['booking_id' => $booking1->id], [
            'method' => 'upi',
            'status' => 'captured',
            'amount' => 799.00,
            'gateway' => 'razorpay',
            'gateway_txn_id' => 'pay_'.Str::random(14),
        ]);

        // Booking 2: In Progress
        $booking2 = Booking::firstOrCreate(['customer_id' => $customer2->id, 'total' => 349.00], [
            'provider_id' => $pro1->user_id,
            'address_id' => $addr1->id,
            'mode' => 'at_customer',
            'status' => 'in_progress',
            'scheduled_start' => now()->setHour(14)->setMinute(0),
            'subtotal' => 349.00,
            'discount' => 0,
            'tax' => 0,
            'completion_otp' => '9103',
        ]);

        BookingItem::firstOrCreate(['booking_id' => $booking2->id, 'service_id' => $leakRepair->id], [
            'quantity' => 1,
            'unit_price' => 349.00,
            'line_total' => 349.00,
        ]);

        // 9. Seed Support Ticket
        SupportTicket::firstOrCreate(['raised_by' => $customer1->id, 'category' => 'damage'], [
            'booking_id' => $booking1->id,
            'description' => 'Minor scratch on wall while moving the indoor unit.',
            'status' => 'open',
        ]);
    }
}

