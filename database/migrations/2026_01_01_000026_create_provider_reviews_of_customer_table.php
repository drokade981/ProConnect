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
        Schema::create('provider_reviews_of_customer', function (Blueprint $table) {
            $table->uuid('booking_id')->primary();
            $table->foreign('booking_id')->references('id')->on('bookings')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating')->nullable(); // 1 to 5
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_reviews_of_customer');
    }
};

