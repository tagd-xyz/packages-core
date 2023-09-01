<?php

namespace Tagd\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Tagd\Core\Models\Item\Type;

class DevSeeder extends Seeder
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

        $this->call(Actors\ConsumersSeeder::class, false, [
            'options' => [
                'total' => 25,
            ],
        ]);
        $this->call(Actors\ResellersSeeder::class);
        $this->call(Actors\RetailersSeeder::class);
        foreach ([
            'Footwear',
            'Handbags',
        ] as $typeName) {
            $type = Type::where('name', $typeName)->firstOrFail();
            $this->call(Items\StockSeeder::class, false, [
                ['type' => $type],
            ]);
        }
        $this->call(Items\ItemsSeeder::class);
    }
}
