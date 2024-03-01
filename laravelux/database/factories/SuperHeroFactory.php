<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SuperHeroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid,
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'heroname' => $this->faker->word,
            'sexe' => $this->faker->randomElement(['homme', 'femme', 'autre']),
            'hairColor' => $this->faker->colorName,
            'description' => $this->faker->sentence,
            'linkImage' => "https://picsum.photos/id/237/200/300",
            'originPlannet' => rand(1, 5),
            'vehicle' => rand(1, 5),
            'team' => $this->faker->randomElement([1,2,3,4,null]),
            'creatorId' => rand(1, 10),
        ];
    }
}
