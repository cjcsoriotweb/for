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
        Schema::create('chat_with_bots', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->longText('reply')->nullable();
            $table->integer('user_id')->default(0);
            $table->integer('conversation')->default(0);
            $table->boolean('see')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_with_bots');
    }
};
