<?php

namespace Tagd\Core\Database\Factories\Actor;

use Illuminate\Database\Eloquent\Factories\Factory;

class Reseller extends Factory
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
            'email' => $this->faker->email(),
        ];
    }
}
