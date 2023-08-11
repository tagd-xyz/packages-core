<?php

namespace Tagd\Core\Database\Seeders\Actors;

use Illuminate\Database\Seeder;
use Tagd\Core\Database\Seeders\Traits\TruncatesTables;
use Tagd\Core\Database\Seeders\Traits\UsesFactories;
use Tagd\Core\Models\Actor\Consumer;

class ConsumersSeeder extends Seeder
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
                (new Consumer())->getTable(),
            ]);
        }

        if (empty(Consumer::count())) {
            $factory = Consumer::factory()
                ->count($total)
                ->create();
        }
    }
}
