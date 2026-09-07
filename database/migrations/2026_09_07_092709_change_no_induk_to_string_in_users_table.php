<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * `no_induk` was declared as a 4-byte `integer` (max 2,147,483,647), but every
     * validation rule in the app (RegisterController, SuperController, ...) treats it
     * as a free-form string up to 50 chars. Newer student ID formats (e.g. 2506543855)
     * exceed the int4 range, so the uniqueness-check query during registration throws
     * SQLSTATE[22003] "Numeric value out of range" and blocks account creation entirely.
     * Widening to varchar(50) matches the actual validation contract and fixes the overflow.
     */
    public function up(): void
    {
        // Production runs Postgres only; sqlite (used by the test suite) has dynamic
        // column typing and never enforced the int4 range, so there's nothing to widen there.
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE users ALTER COLUMN no_induk TYPE VARCHAR(50) USING no_induk::varchar');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE users ALTER COLUMN no_induk TYPE INTEGER USING NULLIF(no_induk, \'\')::integer');
        }
    }
};
