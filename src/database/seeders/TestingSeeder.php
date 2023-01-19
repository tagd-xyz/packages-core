<?php

namespace Tagd\Core\Database\Seeders;

use Illuminate\Database\Seeder;

class TestingSeeder extends Seeder
{
    use UsesFactories;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->setupFactories();

        $this->call(DatabaseSeeder::class);
    }
}
