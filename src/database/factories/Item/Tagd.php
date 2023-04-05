<?php

namespace Tagd\Core\Database\Factories\Item;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tagd\Core\Models\Item\TagdStatus;

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
     */
    public function active(bool $isActive = true): self
    {
        return $this->state(function (array $attributes) use ($isActive) {
            return [
                'status' => $isActive ? TagdStatus::ACTIVE : TagdStatus::INACTIVE,
                'status_at' => Carbon::now(),
            ];
        });
    }
}
