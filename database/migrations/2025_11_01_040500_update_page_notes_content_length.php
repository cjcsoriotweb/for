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
        if (! Schema::hasTable('page_notes') || ! Schema::hasColumn('page_notes', 'content')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE page_notes MODIFY content LONGTEXT');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE page_notes ALTER COLUMN content TYPE TEXT');
        } elseif ($driver === 'sqlsrv') {
            DB::statement('ALTER TABLE page_notes ALTER COLUMN content NVARCHAR(MAX)');
        } elseif ($driver === 'sqlite') {
            // SQLite already stores TEXT columns with flexible capacity.
            // Nothing to do.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('page_notes') || ! Schema::hasColumn('page_notes', 'content')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE page_notes MODIFY content TEXT');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE page_notes ALTER COLUMN content TYPE TEXT');
        } elseif ($driver === 'sqlsrv') {
            DB::statement('ALTER TABLE page_notes ALTER COLUMN content NVARCHAR(4000)');
        }
        // No specific rollback for SQLite.
    }
};
