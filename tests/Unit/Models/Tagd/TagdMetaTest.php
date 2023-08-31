<?php

namespace Tagd\Core\Tests\Unit\Models\Tagd;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tagd\Core\Models\Item\TagdStatus;
use Tagd\Core\Tests\TestCase;
use Tagd\Core\Tests\Traits\NeedsTagds;

class TagdMetaTest extends TestCase
{
    use NeedsTagds, RefreshDatabase;

    public function testDefaultState()
    {
        $tagd = $this->aTagd();

        $this->assertEquals(TagdStatus::INACTIVE, $tagd->status);
    }

    public function testActiveState()
    {
        $tagd = $this->aTagd();

        $tagd->activate();

        $this->assertEquals(TagdStatus::ACTIVE, $tagd->status);
    }

    public function testInactiveState()
    {
        $tagd = $this->aTagd();

        $tagd->deactivate();

        $this->assertEquals(TagdStatus::INACTIVE, $tagd->status);
    }

    public function testExpireState()
    {
        $tagd = $this->aTagd();

        $tagd->expire();

        $this->assertEquals(TagdStatus::EXPIRED, $tagd->status);
    }

    public function testTransferState()
    {
        $tagd = $this->aTagd();

        $tagd->transfer();

        $this->assertEquals(TagdStatus::TRANSFERRED, $tagd->status);
    }

    public function testCancelState()
    {
        $tagd = $this->aTagd();

        $tagd->cancel();

        $this->assertEquals(TagdStatus::CANCELLED, $tagd->status);
    }
}
