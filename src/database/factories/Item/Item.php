<?php

namespace Tagd\Core\Database\Factories\Item;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Item\Stock;

class Item extends Factory
{
    private function randomStock(): Stock
    {
        $stock = Stock::inRandomOrder()->first();
        if (! $stock) {
            $stock = Stock::factory()
                ->for(Retailer::factory()->create())
                ->create();
        }

        return $stock;
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $stock = $this->randomStock();

        return [
            'name' => $stock->name,
            'type' => $stock->type,
            'description' => $stock->description,
            'properties' => $stock->properties,
        ];
    }
}
