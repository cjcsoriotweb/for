<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->foreignId('ai_trainer_id')
                ->nullable()
                ->constrained('ai_trainers')
                ->nullOnDelete();
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

        if (Schema::hasColumn('formations', 'primary_ai_trainer_id')) {
            $formations = DB::table('formations')
                ->select('id', 'title', 'primary_ai_trainer_id')
                ->whereNotNull('primary_ai_trainer_id')
                ->get();

            foreach ($formations as $formation) {
                $categoryId = DB::table('formation_categories')->insertGetId([
                    'name' => sprintf('Auto catégorie formation #%d', $formation->id),
                    'description' => null,
                    'ai_trainer_id' => $formation->primary_ai_trainer_id,
                    'created_by' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('formations')
                    ->where('id', $formation->id)
                    ->update(['formation_category_id' => $categoryId]);
            }

            Schema::table('formations', function (Blueprint $table) {
                $table->dropForeign(['primary_ai_trainer_id']);
                $table->dropColumn('primary_ai_trainer_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formations', function (Blueprint $table) {
            if (! Schema::hasColumn('formations', 'primary_ai_trainer_id')) {
                $table->foreignId('primary_ai_trainer_id')
                    ->nullable()
                    ->constrained('ai_trainers')
                    ->nullOnDelete();
            }

            if (Schema::hasColumn('formations', 'formation_category_id')) {
                $table->dropForeign(['formation_category_id']);
                $table->dropColumn('formation_category_id');
            }
        });

        Schema::dropIfExists('formation_categories');
    }
};
