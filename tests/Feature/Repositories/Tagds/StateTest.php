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

    public function testConfirm()
    {
        $repo = app(Tagds::class);

        $parentTagd = $this->aTagd();
        $tagd = $this->aTagdChildOf($parentTagd);
        $consumer = $this->aConsumer();

        $newTagd = $repo->confirm($tagd, $consumer);

        $this->assertEquals($tagd->isTransferred, true);

        $this->assertEquals($newTagd->isActive, true);
        $this->assertEquals($newTagd->consumer_id, $consumer->id);
        $this->assertEquals($newTagd->parent_id, $tagd->id);
        $this->assertEquals($newTagd->item_id, $tagd->item_id);
    }
}
