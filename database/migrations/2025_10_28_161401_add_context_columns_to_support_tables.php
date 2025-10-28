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
        Schema::table('support_tickets', function (Blueprint $table) {
            if (! Schema::hasColumn('support_tickets', 'origin_label')) {
                $table->string('origin_label', 60)->nullable()->after('closed_by');
            }

            if (! Schema::hasColumn('support_tickets', 'origin_path')) {
                $table->string('origin_path')->nullable()->after('origin_label');
            }
        });

        Schema::table('support_ticket_messages', function (Blueprint $table) {
            if (! Schema::hasColumn('support_ticket_messages', 'context_label')) {
                $table->string('context_label', 80)->nullable()->after('read_at');
            }

            if (! Schema::hasColumn('support_ticket_messages', 'context_path')) {
                $table->string('context_path')->nullable()->after('context_label');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            if (Schema::hasColumn('support_tickets', 'origin_path')) {
                $table->dropColumn('origin_path');
            }
            if (Schema::hasColumn('support_tickets', 'origin_label')) {
                $table->dropColumn('origin_label');
            }
        });

        Schema::table('support_ticket_messages', function (Blueprint $table) {
            if (Schema::hasColumn('support_ticket_messages', 'context_path')) {
                $table->dropColumn('context_path');
            }
            if (Schema::hasColumn('support_ticket_messages', 'context_label')) {
                $table->dropColumn('context_label');
            }
        });
    }
};
