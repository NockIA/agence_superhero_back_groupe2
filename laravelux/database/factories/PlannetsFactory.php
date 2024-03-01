<?php

namespace Database\Factories;

use App\Models\Plannets;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlannetsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Plannets::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['Mercury', 'Venus', 'Earth', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune']),
            'description' => $this->faker->paragraph(rand(3, 6)),
            'creatorId' => rand(1, 10),
            'linkImage' => "https://picsum.photos/id/141/2048/1365",
        ];
    }
}
