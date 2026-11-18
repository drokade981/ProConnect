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
        Schema::create('provider_ledger', function (Blueprint $table) {
            $table->id();
            $table->uuid('provider_id');
            $table->foreign('provider_id')->references('user_id')->on('providers')->cascadeOnDelete();
            $table->foreignUuid('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->enum('type', [
                'earning',
                'commission',
                'penalty',
                'bonus',
                'payout',
                'refund_adjustment',
                'cash_collected'
            ]);
            $table->decimal('amount', 10, 2); // +credit / -debit
            $table->timestampTz('created_at')->useCurrent();

            $table->index(['provider_id', 'created_at'], 'idx_ledger_provider');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_ledger');
    }
};

