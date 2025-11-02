<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            Schema::create('quizzes_tmp', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('lesson_id')->nullable();
                $table->unsignedBigInteger('formation_id')->nullable();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('type')->default('lesson');
                $table->unsignedInteger('passing_score')->default(70);
                $table->unsignedInteger('max_attempts')->nullable();
                $table->timestamps();
            });

            DB::statement('INSERT INTO quizzes_tmp (id, lesson_id, formation_id, title, description, type, passing_score, max_attempts, created_at, updated_at) SELECT id, lesson_id, NULL, title, description, \'lesson\', passing_score, max_attempts, created_at, updated_at FROM quizzes');

            Schema::drop('quizzes');
            Schema::rename('quizzes_tmp', 'quizzes');

            Schema::table('quizzes', function (Blueprint $table) {
                $table->foreign('lesson_id')
                    ->references('id')
                    ->on('lessons')
                    ->nullOnDelete();

                $table->foreign('formation_id')
                    ->references('id')
                    ->on('formations')
                    ->cascadeOnDelete();
            });

            return;
        }

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropForeign(['lesson_id']);
        });

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE quizzes MODIFY lesson_id BIGINT UNSIGNED NULL');
            DB::statement('ALTER TABLE quizzes ADD CONSTRAINT quizzes_lesson_id_foreign FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE SET NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE quizzes ALTER COLUMN lesson_id DROP NOT NULL');
            DB::statement('ALTER TABLE quizzes ADD CONSTRAINT quizzes_lesson_id_foreign FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE SET NULL');
        } else {
            throw new RuntimeException('Unsupported database driver for entry quiz migration: '.$driver);
        }

        Schema::table('quizzes', function (Blueprint $table) {
            $table->foreignId('formation_id')
                ->nullable()
                ->after('lesson_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('type')
                ->default('lesson')
                ->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->dropForeign(['lesson_id']);
                $table->dropForeign(['formation_id']);
            });

            Schema::create('quizzes_tmp', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('lesson_id');
                $table->string('title');
                $table->text('description')->nullable();
                $table->unsignedInteger('passing_score')->default(70);
                $table->unsignedInteger('max_attempts')->nullable();
                $table->timestamps();
            });

            DB::statement('INSERT INTO quizzes_tmp (id, lesson_id, title, description, passing_score, max_attempts, created_at, updated_at) SELECT id, lesson_id, title, description, passing_score, max_attempts, created_at, updated_at FROM quizzes');

            Schema::drop('quizzes');
            Schema::rename('quizzes_tmp', 'quizzes');

            Schema::table('quizzes', function (Blueprint $table) {
                $table->foreign('lesson_id')
                    ->references('id')
                    ->on('lessons')
                    ->cascadeOnDelete();
            });

            return;
        }

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropForeign(['formation_id']);
            $table->dropColumn(['formation_id', 'type']);
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropForeign(['lesson_id']);
        });

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE quizzes MODIFY lesson_id BIGINT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE quizzes ADD CONSTRAINT quizzes_lesson_id_foreign FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE quizzes ALTER COLUMN lesson_id SET NOT NULL');
            DB::statement('ALTER TABLE quizzes ADD CONSTRAINT quizzes_lesson_id_foreign FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE');
        } else {
            throw new RuntimeException('Unsupported database driver for entry quiz migration: '.$driver);
        }
    }
};
