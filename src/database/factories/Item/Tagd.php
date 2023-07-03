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

    /**
     * Set tagd as resold
     */
    public function resold(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => TagdStatus::RESALE,
                'status_at' => Carbon::now(),
            ];
        });
    }

    /**
     * Set tagd as transferred
     */
    public function transferred(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => TagdStatus::TRANSFERRED,
                'status_at' => Carbon::now(),
            ];
        });
    }

    /**
     * Set tagd parent id
     *
     * @param  mixed  $tagd
     */
    public function childOf($tagd): self
    {
        return $this->state(function (array $attributes) use ($tagd) {
            return [
                'parent_id' => $tagd->id,
            ];
        });
    }

    /**
     * Empty transaction
     *
     * @param  mixed  $id
     */
    public function NoTransaction(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'meta' => [
                    'transaction' => null,
                ],
            ];
        });
    }
}
