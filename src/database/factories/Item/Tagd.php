<?php

namespace Tagd\Core\Database\Factories\Item;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class Tagd extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'meta' => [
                'transaction' => $this->faker->ean13(),
            ],
        ];
    }

    /**
     * Set tagd as active
     *
     * @param  bool  $isActive
     * @return self
     */
    public function active(bool $isActive = true): self
    {
        return $this->state(function (array $attributes) use ($isActive) {
            return [
                'activated_at' => $isActive
                    ? Carbon::now()
                    : null,
            ];
        });
    }
}
