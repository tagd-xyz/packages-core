<?php

namespace Tagd\Core\Database\Seeders\Items;

use Illuminate\Database\Seeder;
use Tagd\Core\Database\Seeders\Traits\TruncatesTables;
use Tagd\Core\Database\Seeders\Traits\UsesFactories;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Item\Stock;

class StockSeeder extends Seeder
{
    use UsesFactories, TruncatesTables;

    /**
     * Seed the application's database for development purposes.
     *
     * @return void
     */
    public function run(array $options = [])
    {
        extract([
            'truncate' => false,
            'total' => 1,
            'retailerId' => null, // null means first
            'type' => null, // null means random
            ...$options,
        ]);

        $this->setupFactories();

        if ($truncate) {
            $this->truncate([
                (new Stock())->getTable(),
            ]);
        }

        $retailer = $retailerId
            ? Retailer::find($retailerId)
            : Retailer::first();

        $factory = Stock::factory()
            ->count($total);

        if ($type) {
            $factory = $factory->type($type);
        }
        $factory
            ->for($retailer)
            ->create();
    }
}
