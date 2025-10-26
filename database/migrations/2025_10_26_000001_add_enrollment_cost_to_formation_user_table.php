<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('formation_user', function (Blueprint $table) {
            $table->integer('enrollment_cost')->nullable()->after('max_score_total');
        });
    }

    public function down(): void
    {
        Schema::table('formation_user', function (Blueprint $table) {
            $table->dropColumn('enrollment_cost');
        });
    }
};

