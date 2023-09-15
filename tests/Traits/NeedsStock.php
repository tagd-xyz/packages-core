<?php

namespace Tagd\Core\Tests\Traits;

use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Item\Stock;

trait NeedsStock
{
    /**
     * Creates a stock
     */
    protected function aStock(array $options = []): Stock
    {
        extract([
            'retailer' => Retailer::factory()->create(),
            ...$options,
        ]);

        return Stock::factory()
            ->for($retailer)
            ->create();
    }
}
