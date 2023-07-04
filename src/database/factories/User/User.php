<?php

namespace Tagd\Core\Database\Factories\User;

use Illuminate\Database\Eloquent\Factories\Factory;

class User extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
        ];
    }

    /**
     * Set firebase tenant
     */
    public function firebase(string $tenant): self
    {
        return $this->state(function (array $attributes) use ($tenant) {
            return [
                'firebase_id' => $this->faker->uuid,
                'firebase_tenant' => $tenant,
            ];
        });
    }
}
