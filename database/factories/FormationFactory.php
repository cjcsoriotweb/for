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
            'level' => $this->faker->randomElement(['debutant', 'intermediaire', 'avancé']),
            'money_amount' => $this->faker->numberBetween(0, 1000),
        ];
    }

    /**
     * Formation débutant
     */
    public function beginner()
    {
        return $this->state(function (array $attributes) {
            return [
                'level' => 'debutant',
                'money_amount' => $this->faker->numberBetween(0, 200),
            ];
        });
    }

    /**
     * Formation intermédiaire
     */
    public function intermediate()
    {
        return $this->state(function (array $attributes) {
            return [
                'level' => 'intermediaire',
                'money_amount' => $this->faker->numberBetween(100, 500),
            ];
        });
    }

    /**
     * Formation avancée
     */
    public function advanced()
    {
        return $this->state(function (array $attributes) {
            return [
                'level' => 'avancé',
                'money_amount' => $this->faker->numberBetween(300, 1000),
            ];
        });
    }
}
