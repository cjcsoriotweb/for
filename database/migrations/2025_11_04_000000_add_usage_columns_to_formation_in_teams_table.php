<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('formation_in_teams', function (Blueprint $table) {
            $table->unsignedInteger('usage_quota')
                ->default(0)
                ->after('visible');
            $table->unsignedInteger('usage_consumed')
                ->default(0)
                ->after('usage_quota');
        });
    }

    public function down(): void
    {
        Schema::table('formation_in_teams', function (Blueprint $table) {
            $table->dropColumn(['usage_quota', 'usage_consumed']);
        });
    }
};
