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
            $table->timestamp('completion_request_at')->nullable();
            $table->enum('completion_request_status', ['pending', 'approved', 'rejected'])->nullable();
            $table->foreignId('trainer_signature_id')->nullable()->constrained('signatures')->onDelete('set null');
            $table->timestamp('completion_validated_at')->nullable();
            $table->foreignId('completion_validated_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formation_user', function (Blueprint $table) {
            $table->dropForeign(['trainer_signature_id']);
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
