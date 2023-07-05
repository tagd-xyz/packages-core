<?php

namespace Tagd\Core\Tests\Feature\Services\TrustScores;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tagd\Core\Models\Ref\TrustSetting;
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

        // sale an item, and resale it
        $retailer = $this->aRetailer();
        $stock = $this->anItem();
        $item = $retailerSalesService->processRetailerSale(
            $retailer->id,
            'consumer@gmail.com',
            'transaction123',
            [
                'name' => $stock->name,
                'description' => $stock->description,
                'type' => $stock->type,
                'properties' => [
                    ...$stock->properties,
                    'brand' => 'gucci',
                ],
            ],
            []
        );
        $tagd = $item->tagds->first();

        // initial trust score must be 0 (default)
        $this->assertEquals(TrustSetting::SCORE_DEFAULT, $tagd->trust_score);

        // resale the item
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
        // +5 for gucci brand
        // +5 for consumer trust score
        // +20 for 1 hour resale
        $this->assertEquals(30, $tagdResale->trust_score);
    }
}
