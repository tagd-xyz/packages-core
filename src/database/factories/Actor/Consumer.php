<?php

namespace Tagd\Core\Database\Factories\Actor;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'name' => $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'email' => $this->faker->email(),
        ];
    }

    /**
     * Set random email
     */
    public function randomEmail(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'email' => Str::orderedUuid()->toString() . 'gmail.com',
            ];
        });
    }
}
