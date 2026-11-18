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
        Schema::create('provider_availability', function (Blueprint $table) {
            $table->id();
            $table->uuid('provider_id');
            $table->foreign('provider_id')->references('user_id')->on('providers')->cascadeOnDelete();
            $table->unsignedTinyInteger('weekday'); // 0 = Sunday, 6 = Saturday
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_availability');
    }
};

