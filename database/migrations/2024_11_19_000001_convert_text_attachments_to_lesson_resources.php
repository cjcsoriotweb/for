<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('text_content_attachments') && ! Schema::hasTable('lesson_resources')) {
            Schema::rename('text_content_attachments', 'lesson_resources');
        }

        if (! Schema::hasTable('lesson_resources')) {
            Schema::create('lesson_resources', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
                $table->string('name');
                $table->string('file_path');
                $table->string('mime_type')->nullable();
                $table->string('display_mode', 20)->default('download');
                $table->timestamps();
            });

            return;
        }

        Schema::table('lesson_resources', function (Blueprint $table) {
            if (! Schema::hasColumn('lesson_resources', 'lesson_id')) {
                $table->foreignId('lesson_id')->nullable()->after('id');
            }
        });

        if (Schema::hasColumn('lesson_resources', 'text_content_id')) {
            DB::statement('
                UPDATE lesson_resources lr
                INNER JOIN text_contents tc ON lr.text_content_id = tc.id
                SET lr.lesson_id = tc.lesson_id
                WHERE lr.lesson_id IS NULL
            ');
        }

        if (Schema::hasColumn('lesson_resources', 'lesson_id')) {
            DB::statement('ALTER TABLE lesson_resources MODIFY lesson_id BIGINT UNSIGNED NOT NULL');
        }

        if ($this->foreignKeyExists('lesson_resources', 'lesson_resources_lesson_id_foreign')) {
            Schema::table('lesson_resources', fn (Blueprint $table) => $table->dropForeign('lesson_resources_lesson_id_foreign'));
        }

        if ($this->foreignKeyExists('lesson_resources', 'lesson_resources_text_content_id_foreign')) {
            Schema::table('lesson_resources', fn (Blueprint $table) => $table->dropForeign('lesson_resources_text_content_id_foreign'));
        } elseif ($this->foreignKeyExists('lesson_resources', 'text_content_attachments_text_content_id_foreign')) {
            Schema::table('lesson_resources', fn (Blueprint $table) => $table->dropForeign('text_content_attachments_text_content_id_foreign'));
        }

        Schema::table('lesson_resources', function (Blueprint $table) {
            if (Schema::hasColumn('lesson_resources', 'text_content_id')) {
                $table->dropColumn('text_content_id');
            }

            if (! Schema::hasColumn('lesson_resources', 'lesson_id')) {
                return;
            }

            $table->foreign('lesson_id')->references('id')->on('lessons')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('lesson_resources')) {
            return;
        }

        Schema::table('lesson_resources', function (Blueprint $table) {
            if (! Schema::hasColumn('lesson_resources', 'text_content_id')) {
                $table->foreignId('text_content_id')->nullable()->after('lesson_id')->constrained('text_contents')->nullOnDelete();
            }
        });

        if (Schema::hasColumn('lesson_resources', 'text_content_id')) {
            DB::statement('
                UPDATE lesson_resources lr
                INNER JOIN text_contents tc ON tc.lesson_id = lr.lesson_id
                SET lr.text_content_id = tc.id
                WHERE lr.text_content_id IS NULL
            ');
        }

        if ($this->foreignKeyExists('lesson_resources', 'lesson_resources_lesson_id_foreign')) {
            Schema::table('lesson_resources', fn (Blueprint $table) => $table->dropForeign('lesson_resources_lesson_id_foreign'));
        }

        if (Schema::hasTable('lesson_resources') && ! Schema::hasTable('text_content_attachments')) {
            Schema::rename('lesson_resources', 'text_content_attachments');
        }
    }

    private function foreignKeyExists(string $table, string $constraint): bool
    {
        $database = DB::getDatabaseName();

        $result = DB::selectOne('
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?
        ', [$database, $table, $constraint]);

        return $result !== null;
    }
};
