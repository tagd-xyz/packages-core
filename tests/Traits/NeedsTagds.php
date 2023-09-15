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
    protected function aTagd(array $options = []): Tagd
    {
        extract([
            'retailer' => Retailer::factory()->create(),
            'consumer' => Consumer::factory()->create(),
            ...$options,
        ]);

        return Tagd::factory()
            ->for($consumer)
            ->for(Item::factory()
                ->for($retailer)
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
