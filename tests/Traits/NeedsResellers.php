<?php

namespace Tagd\Core\Tests\Traits;

use Tagd\Core\Models\Actor\Reseller;

trait NeedsResellers
{
    /**
     * Creates a reseller
     */
    protected function aReseller(): Reseller
    {
        return Reseller::factory()
            ->create();
    }
}
