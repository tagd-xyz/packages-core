<?php

namespace Tagd\Core\Database\Seeders\Actors;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Tagd\Core\Database\Seeders\Traits\UsesFactories;
use Tagd\Core\Models\Actor\Reseller;

class ResellersSeeder extends Seeder
{
    use UsesFactories;

    /**
     * Seed the application's database for development purposes.
     *
     * @param  array  $options
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
            $this->truncate();
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
                (new Reseller())->getTable(),
            ] as $table
        ) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
