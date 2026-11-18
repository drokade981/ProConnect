<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->uuid('user_id')->primary();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->enum('kind', ['individual', 'agency'])->default('individual');
            $table->uuid('agency_id')->nullable();
            $table->foreign('agency_id')->references('user_id')->on('providers')->nullOnDelete();
            $table->string('display_name');
            $table->text('bio')->nullable();
            $table->integer('experience_months')->default(0);
            $table->enum('verification', ['pending', 'in_review', 'approved', 'rejected', 'expired'])->default('pending');
            $table->boolean('is_online')->default(false);
            $table->geography('last_location', subtype: 'point', srid: 4326)->nullable();
            $table->timestampTz('last_seen_at')->nullable();
            $table->decimal('avg_rating', 3, 2)->default(0.00);
            $table->integer('rating_count')->default(0);
            $table->integer('jobs_completed')->default(0);
            $table->decimal('acceptance_rate', 5, 2)->default(100.00);
            $table->timestampTz('onboarded_at')->nullable();
            $table->timestamps();

            if (DB::getDriverName() === 'pgsql') {
                $table->spatialIndex('last_location', 'idx_providers_geo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};

