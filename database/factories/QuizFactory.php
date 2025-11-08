<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lesson_id' => \App\Models\Lesson::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->sentence(12),
            'type' => \App\Models\Quiz::TYPE_LESSON,
            'passing_score' => 70,
            'max_attempts' => 3,
            'estimated_duration_minutes' => $this->faker->numberBetween(5, 30),
        ];
    }
}
