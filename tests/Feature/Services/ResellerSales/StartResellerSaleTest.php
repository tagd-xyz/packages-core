<?php

namespace Tagd\Core\Tests\Feature\Services\ResellerSales;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tagd\Core\Models\Item\TagdStatus;
use Tagd\Core\Services\ResellerSales\Service as ResellerSalesService;
use Tagd\Core\Tests\TestCase;
use Tagd\Core\Tests\Traits\NeedsItems;
use Tagd\Core\Tests\Traits\NeedsResellers;
use Tagd\Core\Tests\Traits\NeedsTagds;

class StartResellerSaleTest extends TestCase
{
    use RefreshDatabase,
        NeedsItems,
        NeedsTagds,
        NeedsResellers;

    public function testStartResale()
    {
        $service = app(ResellerSalesService::class);

        $reseller = $this->aReseller();
        $tagd = $this->aTagd();

        $newTagd = $service->startResellerSale(
            $reseller,
            $tagd,
        );

        $this->assertEquals($newTagd->status, (TagdStatus::RESALE));
        $this->assertEquals($newTagd->parent_id, $tagd->id);
        $this->assertEquals($newTagd->reseller_id, $reseller->id);
        $this->assertEquals($newTagd->item_id, $tagd->item_id);
    }
}
