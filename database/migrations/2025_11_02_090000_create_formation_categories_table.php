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
        Schema::create('formation_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamps();
        });

        Schema::table('formations', function (Blueprint $table) {
            $table->foreignId('formation_category_id')
                ->nullable()
                ->after('user_id')
                ->constrained('formation_categories')
                ->nullOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formations', function (Blueprint $table) {
            if (Schema::hasColumn('formations', 'formation_category_id')) {
                $table->dropForeign(['formation_category_id']);
                $table->dropColumn('formation_category_id');
            }
        });

        Schema::dropIfExists('formation_categories');
    }
};
