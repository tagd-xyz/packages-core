<?php

namespace Tagd\Core\Database\Seeders\Items;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Tagd\Core\Database\Seeders\Traits\UsesFactories;
use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Item\Item;
use Tagd\Core\Models\Item\Tagd;

class ItemsSeeder extends Seeder
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
            'truncate' => true,
            'total' => 10,
            ...$options,
        ]);

        $this->setupFactories();

        if ($truncate) {
            $this->truncate();
        }

        $factory = Item::factory()
            ->count($total)
            ->for(Retailer::first())
            ->has(
                Tagd::factory()
                    ->count(1)
                    ->active()
                    ->for(Consumer::first()),
                'tagds'
            )
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
                (new Tagd())->getTable(),
                (new Item())->getTable(),
            ] as $table
        ) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
