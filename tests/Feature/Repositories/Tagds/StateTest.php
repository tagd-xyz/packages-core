<?php

namespace Tagd\Core\Tests\Feature\Repositories\Tagds;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tagd\Core\Repositories\Items\Tagds;
use Tagd\Core\Tests\TestCase;
use Tagd\Core\Tests\Traits\NeedsConsumers;
use Tagd\Core\Tests\Traits\NeedsTagds;

class StateTest extends TestCase
{
    use RefreshDatabase,
        NeedsTagds,
        NeedsConsumers;

    // public function testActivate()
    // {
    //     $repo = app(Tagds::class);

    //     $tagd = $this->aTagd();
    //     $repo->activate($tagd);

    //     $this->assertEquals($tagd->isActive, true);
    // }

    public function testSetAsAvailableForResale()
    {
        $repo = app(Tagds::class);

        $tagd = $this->aTagd();
        $repo->setAsAvailableForResale($tagd);

        $this->assertEquals($tagd->isAvailableForResale, true);
    }

    public function testCancel()
    {
        $repo = app(Tagds::class);

        $tagd = $this->aTagd();
        $repo->cancel($tagd);

        $this->assertEquals($tagd->isCancelled, true);
    }
}
