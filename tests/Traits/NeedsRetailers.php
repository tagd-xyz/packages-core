<?php

namespace Tagd\Core\Tests\Traits;

use Tagd\Core\Models\Actor\Retailer;

trait NeedsRetailers
{
    /**
     * Creates a retailer
     */
    protected function aRetailer(): Retailer
    {
        return Retailer::factory()
            ->create();
    }
}
