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
        Schema::table('formation_user', function (Blueprint $table) {
            if (!Schema::hasColumn('formation_user', 'completion_request_at')) {
                $table->timestamp('completion_request_at')->nullable();
            }
            if (!Schema::hasColumn('formation_user', 'completion_request_status')) {
                $table->enum('completion_request_status', ['pending', 'approved', 'rejected'])->nullable();
            }
            if (!Schema::hasColumn('formation_user', 'trainer_signature_id')) {
                $table->unsignedBigInteger('trainer_signature_id')->nullable();
            }
            if (!Schema::hasColumn('formation_user', 'completion_validated_at')) {
                $table->timestamp('completion_validated_at')->nullable();
            }
            if (!Schema::hasColumn('formation_user', 'completion_validated_by')) {
                $table->foreignId('completion_validated_by')->nullable()->constrained('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formation_user', function (Blueprint $table) {
            $table->dropForeign(['completion_validated_by']);
            $table->dropColumn([
                'completion_request_at',
                'completion_request_status',
                'trainer_signature_id',
                'completion_validated_at',
                'completion_validated_by',
            ]);
        });
    }
};
