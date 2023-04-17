<?php

namespace Tagd\Core\Database\Seeders\Items;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Tagd\Core\Database\Seeders\Traits\UsesFactories;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Item\Stock;

class StockSeeder extends Seeder
{
    use UsesFactories;

    /**
     * Seed the application's database for development purposes.
     *
     * @return void
     */
    public function run(array $options = [])
    {
        extract([
            'truncate' => false,
            'total' => 10,
            'retailerId' => null, // null means first
            'type' => null, // null means random
            ...$options,
        ]);

        $this->setupFactories();

        if ($truncate) {
            $this->truncate();
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

    /**
     * Truncate tables
     *
     * @return void
     */
    private function truncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach (
            [
                (new Stock())->getTable(),
            ] as $table
        ) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
