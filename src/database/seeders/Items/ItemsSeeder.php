<?php

namespace Tagd\Core\Database\Seeders\Items;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tagd\Core\Database\Seeders\Traits\UsesFactories;
use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Item\Item;
use Tagd\Core\Models\Item\Tagd;
use Tagd\Core\Models\Item\TagdStatus;

class ItemsSeeder extends Seeder
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
            'total' => 10,
            ...$options,
        ]);

        $this->setupFactories();

        if ($truncate) {
            $this->truncate();
        }

        $date = Carbon::today()->subDays(30);

        // sell some items
        $consumer = Consumer::first();
        for ($i = 0; $i < $total; $i++) {
            $date->addDays(1);

            $factory = Item::factory()
                ->count(1)
                ->for(Retailer::first())
                ->has(
                    Tagd::factory()
                        ->count(1)
                        ->active()
                        ->for($consumer)
                        ->state([
                            'created_at' => $date,
                        ]),
                    'tagds'
                )
                ->state([
                    'created_at' => $date,
                ])
                ->create();
        }

        // resale some items
        $reseller = Reseller::first();
        $consumer2 = Consumer::where('id', '<>', $consumer->id)->first();
        foreach (Tagd::whereStatus(TagdStatus::ACTIVE)->get() as $tagd) {
            $tagd->transfer();

            $tagdReseller = Tagd::factory()
                ->count(1)
                ->active()
                ->for($reseller)
                ->state([
                    'created_at' => $tagd->created_at,
                    'item_id' => $tagd->item_id,
                    'parent_id' => $tagd->id,
                ])
                ->noTransaction()
                ->create();

            $tagdReseller = $tagdReseller[0];
            $tagdReseller->transfer();

            $tagdConsumer = Tagd::factory()
                ->count(1)
                ->active()
                ->for($consumer2)
                ->state([
                    'created_at' => $tagdReseller->created_at,
                    'item_id' => $tagdReseller->item_id,
                    'parent_id' => $tagdReseller->id,
                ])
                ->noTransaction()
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
            ] as $table
        ) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
