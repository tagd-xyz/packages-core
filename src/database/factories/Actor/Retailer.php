<?php

namespace Tagd\Core\Database\Factories\Actor;

use Illuminate\Database\Eloquent\Factories\Factory;

class Retailer extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'auth_id' => $this->faker->email(),
        ];
    }
}
