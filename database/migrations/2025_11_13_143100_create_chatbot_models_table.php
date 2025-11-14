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
        Schema::create('chatbot_models', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        DB::table('chatbot_models')->insert([
            [
                'key' => 'maonnerie-',
                'name' => 'Maçon',
                'image' => '/images/chatbot/models/macon.png',
                'description' => 'Specialiste des reponses liees a la maçonnerie et aux travaux de gros oeuvre.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('chatbot_models')->insert([
            [
                'key' => 'dev-',
                'name' => 'Assistant Evolubat',
                'image' => '/images/chatbot/models/macon.png',
                'description' => 'Specialiste des reponses liees a la maçonnerie et aux travaux de gros oeuvre.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_models');
    }
};
