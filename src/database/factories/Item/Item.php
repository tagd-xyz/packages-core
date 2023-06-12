<?php

namespace Tagd\Core\Database\Factories\Item;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tagd\Core\Models\Item\Stock;

class Item extends Factory
{
    private function randomStock(): Stock
    {
        return Stock::inRandomOrder()->first();
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
