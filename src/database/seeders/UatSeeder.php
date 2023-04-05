<?php

namespace Tagd\Core\Database\Seeders;

use Illuminate\Database\Seeder;

class UatSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(
        array $options = [],
    ) {
        extract([
            ...$options,
        ]);

        $this->call(DatabaseSeeder::class);
    }
}
