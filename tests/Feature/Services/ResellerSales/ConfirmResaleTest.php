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
    use NeedsConsumers,
        NeedsItems,
        NeedsResellers,
        NeedsTagds,
        RefreshDatabase;

    public function testConfirm()
    {
        $service = app(ResellerSalesService::class);

        $parentTagd = $this->aTagd();
        $tagd = $this->aTagdChildOf($parentTagd);
        $consumer = $this->aConsumer();
        $price = [
            'amount' => 100,
            'currency' => 'GBP',
        ];

        $newTagd = $service->confirmResale($tagd, $consumer, [
            'price' => $price,
        ]);

        $this->assertEquals($tagd->isTransferred, true);

        $this->assertEquals($tagd->meta['price']['amount'], $price['amount']);
        $this->assertEquals($tagd->meta['price']['currency'], $price['currency']);

        $this->assertEquals($newTagd->isActive, true);
        $this->assertEquals($newTagd->consumer_id, $consumer->id);
        $this->assertEquals($newTagd->parent_id, $tagd->id);
        $this->assertEquals($newTagd->item_id, $tagd->item_id);
    }
}
