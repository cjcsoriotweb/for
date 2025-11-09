<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('formation_user', function (Blueprint $table) {
            if (!Schema::hasColumn('formation_user', 'need_verif')) {
                $table->boolean('need_verif')->default(false)->after('completion_request_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('formation_user', function (Blueprint $table) {
            if (Schema::hasColumn('formation_user', 'need_verif')) {
                $table->dropColumn('need_verif');
            }
        });
    }
};

