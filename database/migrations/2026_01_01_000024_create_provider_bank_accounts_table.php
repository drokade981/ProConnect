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
        Schema::create('provider_bank_accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('provider_id');
            $table->foreign('provider_id')->references('user_id')->on('providers')->cascadeOnDelete();
            $table->string('account_name')->nullable();
            $table->binary('account_no_enc')->nullable();
            $table->string('ifsc')->nullable();
            $table->string('upi_id')->nullable();
            $table->boolean('is_primary')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_bank_accounts');
    }
};

