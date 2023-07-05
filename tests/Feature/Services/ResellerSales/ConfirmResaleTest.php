<?php

namespace Tagd\Core\Tests\Feature\Services\ResellerSales;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tagd\Core\Services\ResellerSales\Service as ResellerSalesService;
use Tagd\Core\Tests\TestCase;
use Tagd\Core\Tests\Traits\NeedsConsumers;
use Tagd\Core\Tests\Traits\NeedsItems;
use Tagd\Core\Tests\Traits\NeedsResellers;
use Tagd\Core\Tests\Traits\NeedsTagds;

class ConfirmResaleTest extends TestCase
{
    use RefreshDatabase,
        NeedsItems,
        NeedsConsumers,
        NeedsTagds,
        NeedsResellers;

    public function testConfirm()
    {
        $service = app(ResellerSalesService::class);

        $parentTagd = $this->aTagd();
        $tagd = $this->aTagdChildOf($parentTagd);
        $consumer = $this->aConsumer();

        $newTagd = $service->confirmResale($tagd, $consumer);

        $this->assertEquals($tagd->isTransferred, true);

        $this->assertEquals($newTagd->isActive, true);
        $this->assertEquals($newTagd->consumer_id, $consumer->id);
        $this->assertEquals($newTagd->parent_id, $tagd->id);
        $this->assertEquals($newTagd->item_id, $tagd->item_id);
    }
}
