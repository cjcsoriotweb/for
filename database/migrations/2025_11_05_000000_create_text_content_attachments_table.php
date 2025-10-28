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
        Schema::create('text_content_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('text_content_id')->constrained('text_contents')   // references id on text_contents
      ->cascadeOnDelete();  
            $table->string('name');
            $table->string('file_path');
            $table->string('mime_type')->nullable();
            $table->string('display_mode', 20)->default('download');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('text_content_attachments');
    }
};
