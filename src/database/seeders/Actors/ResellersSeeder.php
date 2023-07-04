<?php

namespace Tagd\Core\Database\Seeders\Actors;

use Illuminate\Database\Seeder;
use Tagd\Core\Database\Seeders\Traits\TruncatesTables;
use Tagd\Core\Database\Seeders\Traits\UsesFactories;
use Tagd\Core\Models\Actor\Reseller;

class ResellersSeeder extends Seeder
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
            'total' => 1,
            ...$options,
        ]);

        $this->setupFactories();

        if ($truncate) {
            $this->truncate([
                (new Reseller())->getTable(),
            ]);
        }

        foreach ([
            'Vinted',
            'Ebay',
            'Depop',
            'eBid',
        ] as $name) {
            $factory = Reseller::factory()
                ->count(1)
                ->state([
                    'name' => $name,
                ])
                ->create();
        }
    }
}
