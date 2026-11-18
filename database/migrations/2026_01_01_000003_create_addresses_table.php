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
        Schema::create('addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('label')->nullable(); // Home, Office
            $table->string('line1');
            $table->string('line2')->nullable();
            $table->string('landmark')->nullable();
            $table->foreignId('city_id')->constrained('cities');
            $table->string('pincode');
            $table->geography('location', subtype: 'point', srid: 4326);
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->index('user_id', 'idx_addresses_user');
            $table->spatialIndex('location', 'idx_addresses_geo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};

