<?php

namespace Database\Factories;

use App\Models\Formation;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormationFactory extends Factory
{
    protected $model = Formation::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(3),
            'level' => $this->faker->randomElement(['debutant', 'intermediaire', 'avancee']),
        ];
    }

    /**
     * Formation debutant
     */
    public function beginner(): Factory
    {
        return $this->state(fn () => [
            'level' => 'debutant',
        ]);
    }

    /**
     * Formation intermediaire
     */
    public function intermediate(): Factory
    {
        return $this->state(fn () => [
            'level' => 'intermediaire',
        ]);
    }

    /**
     * Formation avancee
     */
    public function advanced(): Factory
    {
        return $this->state(fn () => [
            'level' => 'avancee',
        ]);
    }
}
