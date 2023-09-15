<?php

namespace Tagd\Core\Tests\Feature\Services\TrustScores;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tagd\Core\Models\Item\Item;
use Tagd\Core\Services\ResellerSales\Service as ResellerSalesService;
use Tagd\Core\Services\RetailerSales\Service as RetailerSalesService;
use Tagd\Core\Services\TrustScores\Service as TrustScoresService;
use Tagd\Core\Tests\TestCase;
use Tagd\Core\Tests\Traits\NeedsConsumers;
use Tagd\Core\Tests\Traits\NeedsItems;
use Tagd\Core\Tests\Traits\NeedsResellers;
use Tagd\Core\Tests\Traits\NeedsRetailers;
use Tagd\Core\Tests\Traits\NeedsTagds;

class CalculateForTagdTest extends TestCase
{
    use NeedsConsumers,
        NeedsItems,
        NeedsResellers,
        NeedsRetailers,
        NeedsTagds,
        RefreshDatabase;

    private $retailerSalesService = null;

    public function testCalculateNearestNeighbour()
    {
        $trustScoresService = app(TrustScoresService::class);
        $this->retailerSalesService = app(RetailerSalesService::class);
        $resellerSalesService = app(ResellerSalesService::class);

        // 1. sale and resale an item a number of times
        // --------------------------------------------------------------------

        $retailer = $this->aRetailer();
        $stock = $this->anItem();

        $tagds = collect();
        for ($i = 0; $i < 10; $i++) {
            $item = $this->saleAnItem($retailer, $stock);
            $tagd = $item->tagds->first();

            // fake the trust score
            $tagd->update([
                'trust' => ['score' => $i * 10],
            ]);

            $tagds->push($tagd);

            // Carbon::setTestNow(Carbon::now()->addDays(10));
            $tagdResale = $resellerSalesService->startResellerSale(
                $this->aReseller(),
                $tagd
            );

            // fake the trust score
            $tagdResale->update([
                'trust' => ['score' => $tagd->trust_score + 5],
            ]);

            $tagd2 = $resellerSalesService->confirmResale(
                $tagdResale,
                $this->aConsumer(),
                $tagdResale->meta
            );

            // fake the trust score
            $tagd2->update([
                'trust' => ['score' => $tagdResale->trust_score + 5],
            ]);
        }

        // 2. sale the item once again
        // --------------------------------------------------------------------

        $item = $this->saleAnItem($retailer, $stock);
        $tagd = $item->tagds->first();

        // trigger calculation manually instead of using event
        $trustScoresService->calculateForTagd($tagd);

        // initial trust score must be ...
        // +0 as default
        // +4 for nearest neighbour
        // +5 for gucci brand
        $this->assertEquals(9, $tagd->trust_score);
    }

    private function saleAnItem($retailer, $stock): Item
    {
        return $this->retailerSalesService->processRetailerSale(
            $retailer->id,
            'consumer@gmail.com',
            'transaction123',
            [
                'amount' => 100,
                'currency' => 'GBP',
            ],
            [
                'country' => 'GBR',
                'city' => 'London',
            ],
            [
                'name' => $stock->name,
                'description' => $stock->description,
                'type_id' => $stock->type->id,
                'properties' => [
                    ...$stock->properties,
                    'brand' => 'gucci',
                ],
            ],
            []
        );
    }
}
