<?php

namespace Tagd\Core\Tests\Traits;

use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Item\Item;
use Tagd\Core\Models\Item\Tagd;

trait NeedsTagds
{
    /**
     * Creates a tagd
     */
    protected function aTagd(): Tagd
    {
        return Tagd::factory()
            ->for(Consumer::factory()->create())
            ->for(Item::factory()
                ->for(Retailer::factory()->create())
                ->create())
            ->active(false)
            ->create();
    }

    /**
     * Creates a tagd with a parent
     */
    protected function aTagdChildOf(Tagd $parent): Tagd
    {
        return Tagd::factory()
            ->childOf($parent)
            ->create();
    }
}
