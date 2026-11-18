<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('CREATE EXTENSION IF NOT EXISTS postgis;');
            DB::statement('CREATE EXTENSION IF NOT EXISTS pgcrypto;');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep extensions intact
    }
};

