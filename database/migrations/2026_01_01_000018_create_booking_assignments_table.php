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
        Schema::create('booking_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->uuid('provider_id');
            $table->foreign('provider_id')->references('user_id')->on('providers')->cascadeOnDelete();
            $table->enum('status', ['offered', 'accepted', 'rejected', 'expired', 'cancelled'])->default('offered');
            $table->decimal('distance_km', 6, 2)->nullable();
            $table->timestampTz('offered_at')->useCurrent();
            $table->timestampTz('responded_at')->nullable();
            $table->timestampTz('expires_at')->nullable();
            $table->timestamps();

            $table->index(['provider_id', 'status'], 'idx_assign_provider');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_assignments');
    }
};

