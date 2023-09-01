<?php

namespace Tagd\Core\Database\Factories\Actor;

use Illuminate\Database\Eloquent\Factories\Factory;

class Admin extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'email' => $this->faker->email(),
        ];
    }
}
