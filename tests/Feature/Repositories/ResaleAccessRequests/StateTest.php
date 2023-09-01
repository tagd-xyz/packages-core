<?php

namespace Tagd\Core\Tests\Feature\Repositories\ResaleAccessRequests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tagd\Core\Repositories\Resales\AccessRequests;
use Tagd\Core\Tests\TestCase;
use Tagd\Core\Tests\Traits\NeedsResaleAccessRequests;

class StateTest extends TestCase
{
    use NeedsResaleAccessRequests, RefreshDatabase;

    public function testPending()
    {
        $repo = app(AccessRequests::class);

        $accessRequest = $this->aResaleAccessRequest();

        $this->assertEquals($accessRequest->isPending, true);
    }

    public function testReject()
    {
        $repo = app(AccessRequests::class);

        $accessRequest = $this->aResaleAccessRequest();

        $repo->reject($accessRequest);

        $this->assertEquals($accessRequest->isRejected, true);
    }

    public function testApprove()
    {
        $repo = app(AccessRequests::class);

        $accessRequest = $this->aResaleAccessRequest();
        $repo->approve($accessRequest);

        $this->assertEquals($accessRequest->isApproved, true);
    }

    public function testRevoke()
    {
        $repo = app(AccessRequests::class);

        $accessRequest = $this->aResaleAccessRequest();

        $repo->approve($accessRequest);
        $repo->reject($accessRequest);

        $this->assertEquals($accessRequest->isRevoked, true);
    }
}
