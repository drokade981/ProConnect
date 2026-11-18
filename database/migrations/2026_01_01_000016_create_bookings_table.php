<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('customer_id')->constrained('users')->cascadeOnDelete();
            $table->uuid('provider_id')->nullable();
            $table->foreign('provider_id')->references('user_id')->on('providers')->nullOnDelete();
            $table->foreignUuid('address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->foreignUuid('quote_offer_id')->nullable()->constrained('quote_offers')->nullOnDelete();
            $table->enum('mode', ['at_customer', 'at_provider', 'online']);
            $table->enum('status', [
                'created',
                'searching',
                'assigned',
                'accepted',
                'en_route',
                'in_progress',
                'completed',
                'cancelled',
                'failed'
            ])->default('created');
            $table->timestampTz('scheduled_start');
            $table->timestampTz('scheduled_end')->nullable();
            $table->timestampTz('actual_start')->nullable();
            $table->timestampTz('actual_end')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('tax', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2);
            $table->string('completion_otp')->nullable();
            $table->text('customer_notes')->nullable();
            $table->foreignUuid('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('cancel_reason')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'created_at'], 'idx_bookings_customer');
            $table->index(['provider_id', 'scheduled_start'], 'idx_bookings_provider');
            $table->index('status', 'idx_bookings_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

