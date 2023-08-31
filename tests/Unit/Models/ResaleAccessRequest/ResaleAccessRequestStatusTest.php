<?php

namespace Tagd\Core\Tests\Unit\Models\ResaleAccessRequest;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tagd\Core\Tests\TestCase;
use Tagd\Core\Tests\Traits\NeedsResaleAccessRequests;

class ResaleAccessRequestStatusTest extends TestCase
{
    use NeedsResaleAccessRequests, RefreshDatabase;

    public function testDefaultState()
    {
        $accessRequest = $this->aResaleAccessRequest();

        $this->assertEquals($accessRequest->isApproved, false);
        $this->assertEquals($accessRequest->isRejected, false);
    }

    public function testApproveState()
    {
        $accessRequest = $this->aResaleAccessRequest();

        $accessRequest->approve();

        $this->assertEquals($accessRequest->isApproved, true);
    }

    public function testRejectState()
    {
        $accessRequest = $this->aResaleAccessRequest();

        $accessRequest->reject();

        $this->assertEquals($accessRequest->isRejected, true);
    }
}
