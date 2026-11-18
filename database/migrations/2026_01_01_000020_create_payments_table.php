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
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->enum('method', ['upi', 'card', 'netbanking', 'wallet', 'cash']);
            $table->enum('status', [
                'pending',
                'authorized',
                'captured',
                'failed',
                'refunded',
                'partially_refunded'
            ])->default('pending');
            $table->decimal('amount', 10, 2);
            $table->string('gateway')->nullable(); // razorpay, stripe
            $table->string('gateway_txn_id')->nullable();
            $table->string('idempotency_key')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

