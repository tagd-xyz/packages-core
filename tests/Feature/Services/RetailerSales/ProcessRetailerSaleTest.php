<?php

namespace Tagd\Core\Tests\Feature\Services\RetailerSales;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tagd\Core\Services\RetailerSales\Service as RetailerSalesService;
use Tagd\Core\Tests\TestCase;
use Tagd\Core\Tests\Traits\NeedsConsumers;
use Tagd\Core\Tests\Traits\NeedsItems;
use Tagd\Core\Tests\Traits\NeedsRetailers;
use Tagd\Core\Tests\Traits\NeedsTagds;

class ProcessRetailerSaleTest extends TestCase
{
    use RefreshDatabase,
        NeedsItems,
        NeedsTagds,
        NeedsConsumers,
        NeedsRetailers;

    public function testProcessRetailerSale()
    {
        $service = app(RetailerSalesService::class);

        $consumer = $this->aConsumer();
        $retailer = $this->aRetailer();
        $stock = $this->anItem();
        $transactionId = 'transaction123';
        $price = [
            'amount' => 100,
            'currency' => 'GBP',
        ];
        $location = [
            'country' => 'GBR',
            'city' => 'London',
        ];

        $itemDetails = [
            'name' => 'Name',
            'description' => 'Description',
            'type' => $stock->type,
            'properties' => $stock->properties,
        ];

        $item = $service->processRetailerSale(
            $retailer->id,
            $consumer->email,
            $transactionId,
            $price,
            $location,
            $itemDetails,
            []
        );

        $tagd = $item->tagds->first();

        $this->assertEquals($tagd->consumer_id, $consumer->id);
        $this->assertEquals($tagd->meta['transaction'], $transactionId);
        $this->assertEquals($tagd->meta['price']['amount'], $price['amount']);
        $this->assertEquals($tagd->meta['price']['currency'], $price['currency']);
        $this->assertEquals($tagd->meta['location']['country'], $location['country']);
        $this->assertEquals($tagd->meta['location']['city'], $location['city']);
    }
}
