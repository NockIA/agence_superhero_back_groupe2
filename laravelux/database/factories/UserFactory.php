<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Le modèle associé à cette usine.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Définir les attributs par défaut du modèle.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'firstname' => $this->faker->firstname,
            'lastname' => $this->faker->lastname,
            'email' => $this->faker->unique()->safeEmail,
            'password' => "password",
            'linkProfileImage'=>'https://picsum.photos/200',
            'isAdmin' => false,
        ];
    }
}