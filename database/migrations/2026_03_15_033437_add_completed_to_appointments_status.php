<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'])) {
            DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled', 'completed') NOT NULL DEFAULT 'pending'");
        } elseif ($driver === 'pgsql') {
            // Convert enum (if present) to varchar/text and set default to 'pending'
            DB::statement("ALTER TABLE appointments ALTER COLUMN status TYPE VARCHAR(20) USING status::text;");
            DB::statement("ALTER TABLE appointments ALTER COLUMN status SET DEFAULT 'pending';");
        } else {
            // Fallback: try to set the column to string via schema builder if available
            DB::statement("ALTER TABLE appointments ALTER COLUMN status TYPE VARCHAR;");
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'])) {
            DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled') NOT NULL DEFAULT 'pending'");
        } elseif ($driver === 'pgsql') {
            // For pgsql fallback, leave as varchar — reverting to enum automatically is non-trivial
            DB::statement("ALTER TABLE appointments ALTER COLUMN status SET DEFAULT 'pending';");
        }
    }
};