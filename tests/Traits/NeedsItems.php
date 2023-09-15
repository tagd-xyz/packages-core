<?php

namespace Tagd\Core\Tests\Traits;

use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Item\Item;

trait NeedsItems
{
    /**
     * Creates a tagd
     */
    protected function anItem(array $options = []): Item
    {
        extract([
            'retailer' => Retailer::factory()->create(),
            ...$options,
        ]);

        return Item::factory()
            ->for($retailer)
            ->create();
    }
}
