<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('page_notes') || ! Schema::hasColumn('page_notes', 'user_id')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE page_notes MODIFY user_id BIGINT UNSIGNED NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE page_notes ALTER COLUMN user_id DROP NOT NULL');
        } elseif ($driver === 'sqlsrv') {
            DB::statement('ALTER TABLE page_notes ALTER COLUMN user_id BIGINT NULL');
        }
        // SQLite handles nullable columns flexibly; no change required.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('page_notes') || ! Schema::hasColumn('page_notes', 'user_id')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('UPDATE page_notes SET user_id = 1 WHERE user_id IS NULL');
            DB::statement('ALTER TABLE page_notes MODIFY user_id BIGINT UNSIGNED NOT NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('UPDATE page_notes SET user_id = 1 WHERE user_id IS NULL');
            DB::statement('ALTER TABLE page_notes ALTER COLUMN user_id SET NOT NULL');
        } elseif ($driver === 'sqlsrv') {
            DB::statement('UPDATE page_notes SET user_id = 1 WHERE user_id IS NULL');
            DB::statement('ALTER TABLE page_notes ALTER COLUMN user_id BIGINT NOT NULL');
        }
        // SQLite rollback would require manual table rebuild; omitted intentionally.
    }
};

