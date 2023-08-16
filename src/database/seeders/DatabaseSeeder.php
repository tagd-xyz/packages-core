<?php

namespace Tagd\Core\Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(Ref\TrustSettingsSeeder::class);
        $this->call(Ref\CurrenciesSeeder::class);
        $this->call(Items\TypesSeeder::class);
    }
}
