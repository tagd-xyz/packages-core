<?php

namespace Tagd\Core\Database\Seeders\Actors;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Tagd\Core\Database\Seeders\Traits\UsesFactories;
use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Item\Item;
use Tagd\Core\Models\Item\Tagd;
use Tagd\Core\Models\Item\Type;

class RetailersSeeder extends Seeder
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
            'total' => 1,
            ...$options,
        ]);

        $this->setupFactories();

        if ($truncate) {
            $this->truncate();
        }

        foreach ([
            'Top Fashion',
            'Dresses & More',
        ] as $name) {
            $factory = Retailer::factory()
                ->count(1)
                ->state([
                    'name' => $name,
                ])
                ->has(Item::factory()
                    ->count($total)
                    ->type(Type::FASHION)
                    ->has(Tagd::factory()
                        ->count(1)
                        ->for(Consumer::factory())
                        ->active(),
                        'tagds'
                    )
                )
                ->create();
        }

        foreach ([
            'Sneaker World',
            'Kick Game',
        ] as $name) {
            $factory = Retailer::factory()
                ->count(1)
                ->state([
                    'name' => $name,
                ])
                ->has(Item::factory()
                    ->count($total)
                    ->type(Type::SNEAKERS)
                    ->has(Tagd::factory()
                        ->count(1)
                        ->for(Consumer::factory())
                        ->active(),
                        'tagds'
                    )
                )
                ->create();
        }
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
                (new Retailer())->getTable(),
            ] as $table
        ) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
