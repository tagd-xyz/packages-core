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
use Tagd\Core\Services\ResellerSales\Service as ResellerSalesService;

class ItemsSeeder extends Seeder
{
    use TruncatesTables, UsesFactories;

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
            'totalResales' => 10,
            ...$options,
        ]);

        $this->setupFactories();

        if ($truncate) {
            $this->truncate([
                (new Tagd())->getTable(),
                (new Item())->getTable(),
            ]);
        }

        if (! empty(Item::count())) {
            return;
        }

        $resellerSalesService = app()->make(ResellerSalesService::class);

        $date = Carbon::today()->subMonth(1);

        $retailer = Retailer::first();
        $consumer = Consumer::first();

        // retailer sell some items
        // for ($i = 0; $i < $total; $i++) {
        while ($total-- > 0) {
            $date->addDays(rand(1, 5));
            Carbon::setTestNow($date);

            Item::factory()
                // ->count(rand(1, 2))
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

        foreach (Tagd::whereStatus(TagdStatus::ACTIVE)->get() as $tagd) {

            $consumers = collect($consumer->id);
            $activeTagd = $tagd;

            while ($totalResales-- > 0) {
                $newConsumer = Consumer::whereNotIn('id', $consumers)->first();

                $consumers->push($newConsumer->id);

                $days = rand(1, 5);

                $listedAt = $activeTagd->status_at->clone()->addDays($days);
                Carbon::setTestNow($listedAt);

                $tagdReseller = $resellerSalesService->startResellerSale(
                    $reseller,
                    $activeTagd
                );

                $resoldAt = $listedAt->clone()->addDays($days + rand(1, 5));
                Carbon::setTestNow($resoldAt);

                $newAmount = round(
                    $activeTagd->meta['price']['amount'] * ((100 - rand(5, 10)) / 100), 2
                );

                $activeTagd = $resellerSalesService->confirmResale(
                    $tagdReseller,
                    $newConsumer, [
                        'price' => [
                            ...$activeTagd->meta['price'],
                            'amount' => $newAmount,
                        ],
                        'location' => $activeTagd->meta['location'],
                    ]
                );
            }
        }
    }
}
