<?php

namespace Tagd\Core\Database\Seeders\Items;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Tagd\Core\Database\Seeders\Traits\TruncatesTables;
use Tagd\Core\Database\Seeders\Traits\UsesFactories;
use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Item\Item;
use Tagd\Core\Models\Item\Tagd;
use Tagd\Core\Models\Item\TagdStatus;
use Tagd\Core\Repositories\Items\Tagds as TagdsRepo;

class ItemsSeeder extends Seeder
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
            'totalResales' => 1,
            ...$options,
        ]);

        $this->setupFactories();

        if ($truncate) {
            $this->truncate([
                (new Tagd())->getTable(),
                (new Item())->getTable(),
            ]);
        }

        $tagdsRepo = app()->make(TagdsRepo::class);

        $date = Carbon::today()->subMonth(1);

        $retailer = Retailer::first();
        $consumer = Consumer::first();

        // retailer sell some items
        for ($i = 0; $i < $total; $i++) {
            $date->addDays(rand(1, 5));
            Carbon::setTestNow($date);

            Item::factory()
                ->count(rand(1, 5))
                ->for($retailer)
                ->has(
                    Tagd::factory()
                        ->count(1)
                        ->active()
                        ->for($consumer),
                    'tagds'
                )
                ->create();
        }

        // consumer resales some items
        $reseller = Reseller::first();

        $consumers = collect($consumer->id);

        while ($totalResales-- > 0) {
            $newConsumer = Consumer::whereNotIn('id', $consumers)->first();
            foreach (Tagd::whereStatus(TagdStatus::ACTIVE)->get() as $tagd) {

                $days = rand(1, 5);

                $listedAt = $tagd->status_at->clone()->addDays($days);
                Carbon::setTestNow($listedAt);

                $tagdReseller = $tagdsRepo->createForResale($reseller, $tagd);

                $resoldAt = $listedAt->clone()->addDays($days + rand(1, 5));
                Carbon::setTestNow($resoldAt);

                $tagdsRepo->confirm($tagdReseller, $newConsumer);
            }
            $consumers->push($newConsumer->id);
        }
    }
}
