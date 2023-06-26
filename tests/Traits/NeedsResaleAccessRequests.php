<?php

namespace Tagd\Core\Tests\Traits;

use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Resale\AccessRequest;

trait NeedsResaleAccessRequests
{
    /**
     * Creates a resale access request
     */
    protected function aResaleAccessRequest(): AccessRequest
    {

        return AccessRequest::factory()
            ->for(Consumer::factory()->create())
            ->for(Reseller::factory()->create())
            ->create();
    }
}
