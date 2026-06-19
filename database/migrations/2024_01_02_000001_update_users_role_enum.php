<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: expand enum to include ALL values (old + new) — no truncation possible
        DB::unprepared("ALTER TABLE users MODIFY COLUMN role ENUM('admin','standard','product_manager','customer') NOT NULL DEFAULT 'customer'");
        // Step 2: rename existing 'standard' rows to 'customer'
        DB::unprepared("UPDATE users SET role = 'customer' WHERE role = 'standard'");
        // Step 3: drop 'standard' from enum
        DB::unprepared("ALTER TABLE users MODIFY COLUMN role ENUM('admin','product_manager','customer') NOT NULL DEFAULT 'customer'");
    }

    public function down(): void
    {
        DB::unprepared("ALTER TABLE users MODIFY COLUMN role ENUM('admin','standard','product_manager','customer') NOT NULL DEFAULT 'standard'");
        DB::unprepared("UPDATE users SET role = 'standard' WHERE role = 'customer'");
        DB::unprepared("ALTER TABLE users MODIFY COLUMN role ENUM('admin','standard') NOT NULL DEFAULT 'standard'");
    }
};
