<?php

namespace Tagd\Core\Database\Seeders;

use Illuminate\Database\Seeder;

class DevSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $options
     * @return void
     */
    public function run(
        array $options = [],
    ) {
        extract([
            ...$options,
        ]);

        $this->call(DatabaseSeeder::class);

        $this->call(Actors\ConsumersSeeder::class);
        $this->call(Actors\ResellersSeeder::class);
        $this->call(Actors\RetailersSeeder::class);
        // $this->call(Items\ItemsSeeder::class);
    }
}
