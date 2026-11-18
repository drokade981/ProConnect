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
        Schema::create('city_service_prices', function (Blueprint $table) {
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('city_id')->constrained('cities')->cascadeOnDelete();
            $table->decimal('base_price', 10, 2);
            $table->string('unit_label')->nullable(); // 'hour', 'sq ft', 'visit'
            $table->decimal('platform_commission_pct', 5, 2)->default(20.00);
            $table->timestamps();

            $table->primary(['service_id', 'city_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city_service_prices');
    }
};

