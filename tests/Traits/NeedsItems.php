<?php

namespace Tagd\Core\Tests\Traits;

use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Item\Item;

trait NeedsItems
{
    /**
     * Creates a tagd
     */
    protected function anItem(): Item
    {
        return Item::factory()
            ->for(Retailer::factory()->create())
            ->create();
    }
}
