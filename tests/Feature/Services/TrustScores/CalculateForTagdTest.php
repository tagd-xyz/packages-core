<?php

namespace Tagd\Core\Tests\Feature\Services\TrustScores;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
    use RefreshDatabase,
        NeedsItems,
        NeedsTagds,
        NeedsConsumers,
        NeedsRetailers,
        NeedsResellers;

    public function testCalculateForTagd()
    {
        $trustScoresService = app(TrustScoresService::class);
        $retailerSalesService = app(RetailerSalesService::class);
        $resellerSalesService = app(ResellerSalesService::class);

        // 1. sale an item
        // --------------------------------------------------------------------

        $retailer = $this->aRetailer();
        $stock = $this->anItem();
        $item = $retailerSalesService->processRetailerSale(
            $retailer->id,
            'consumer@gmail.com',
            'transaction123',
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
        $tagd = $item->tagds->first();

        // trigger calculation manually instead of using event
        $trustScoresService->calculateForTagd($tagd);

        // initial trust score must be ...
        // +0 as default
        // +5 for gucci brand (already applied)
        $this->assertEquals(5, $tagd->trust_score);

        // 2. resale the item some time later
        // --------------------------------------------------------------------

        Carbon::setTestNow(Carbon::now()->addHours(1));
        $reseller = $this->aReseller();
        $tagdResale = $resellerSalesService->startResellerSale(
            $reseller,
            $tagd
        );
        $tagd->consumer->trust_score = 50;
        $tagd->consumer->save();

        // trigger calculation manually instead of using event
        $trustScoresService->calculateForTagd($tagdResale);

        // resale trust score must be ...
        // +0 as default
        // +0 for gucci brand (already applied)
        // +5 for consumer trust score
        // +20 for 1 hour resale
        $this->assertEquals(30, $tagdResale->trust_score);

        // 3. confirm the resale
        // --------------------------------------------------------------------

        $newConsumer = $this->aConsumer();
        // $newConsumer->trust_score = 50;
        $tagd2 = $resellerSalesService->confirmResale($tagdResale, $newConsumer);

        // trigger calculation manually instead of using event
        $trustScoresService->calculateForTagd($tagd2);

        // new tagds inherit the trust score of the previous tagd
        $this->assertEquals($tagdResale->trust_score, $tagd2->trust_score);

        // 4. resale again
        // --------------------------------------------------------------------
        $newConsumer->trust_score = 50;
        $newConsumer->save();

        Carbon::setTestNow(Carbon::now()->addHours(1));
        $tagdResale2 = $resellerSalesService->startResellerSale(
            $reseller,
            $tagd2
        );

        // trigger calculation manually instead of using event
        $trustScoresService->calculateForTagd($tagdResale2);

        // resale trust score must be ...
        // +initial score
        // +0 for gucci brand (already applied)
        // +5 for consumer trust score
        // +20 for 1 hour resale
        $this->assertEquals($tagdResale->trust_score + 25, $tagdResale2->trust_score);
    }
}
