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
        Schema::create('formation_import_export_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('formation_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['import', 'export']);
            $table->enum('format', ['zip', 'json', 'csv']);
            $table->string('filename')->nullable();
            $table->enum('status', ['success', 'failed', 'partial']);
            $table->text('error_message')->nullable();
            $table->json('stats')->nullable(); // Store statistics like chapters_count, lessons_count, etc.
            $table->integer('file_size')->nullable(); // In bytes
            $table->timestamps();
            
            $table->index(['user_id', 'type', 'created_at']);
            $table->index(['formation_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formation_import_export_logs');
    }
};
