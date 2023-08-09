<?php

namespace Tagd\Core\Database\Seeders\Actors;

use Illuminate\Database\Seeder;
use Tagd\Core\Database\Seeders\Traits\TruncatesTables;
use Tagd\Core\Database\Seeders\Traits\UsesFactories;
use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Item\Item;
use Tagd\Core\Models\Item\Tagd;
use Tagd\Core\Models\Item\Type;

class RetailersSeeder extends Seeder
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
            'truncate' => true,
            'total' => 5,
            ...$options,
        ]);

        $this->setupFactories();

        if ($truncate) {
            $this->truncate([
                (new Tagd())->getTable(),
                (new Item())->getTable(),
                (new Retailer())->getTable(),
            ]);
        }

        foreach ([
            'Top Fashion',
            'Dresses & More',
            'Sneaker World',
            'Kick Game',
        ] as $name) {
            if (empty(Retailer::where('name', $name)->count())) {
                $factory = Retailer::factory()
                    ->count(1)
                    ->state([
                        'name' => $name,
                    ])
                    // ->has(Item::factory()
                    //     ->count($total)
                    //     ->type(Type::FASHION)
                    //     ->has(Tagd::factory()
                    //         ->count(1)
                    //         ->for(Consumer::factory())
                    //         ->active(),
                    //         'tagds'
                    //     )
                    // )
                    ->create();
            }
        }
    }
}
