<?php

namespace Tagd\Core\Database\Factories\Actor;

use Illuminate\Database\Eloquent\Factories\Factory;

class Consumer extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => $this->faker->email(),
        ];
    }
}
