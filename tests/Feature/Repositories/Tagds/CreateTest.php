<?php

namespace Tagd\Core\Tests\Feature\Repositories\Tagds;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tagd\Core\Models\Item\TagdStatus;
use Tagd\Core\Repositories\Items\Tagds;
use Tagd\Core\Tests\TestCase;
use Tagd\Core\Tests\Traits\NeedsConsumers;
use Tagd\Core\Tests\Traits\NeedsItems;
use Tagd\Core\Tests\Traits\NeedsResellers;
use Tagd\Core\Tests\Traits\NeedsTagds;

class CreateTest extends TestCase
{
    use RefreshDatabase,
        NeedsItems,
        NeedsTagds,
        NeedsConsumers,
        NeedsResellers;

    public function testCreateFor()
    {
        $repo = app(Tagds::class);

        $item = $this->anItem();
        $consumer = $this->aConsumer();

        $tagd = $repo->createFor(
            $item,
            $consumer,
            '1234567890'
        );

        $this->assertEquals($tagd->meta['transaction'], '1234567890');
        $this->assertEquals($tagd->item_id, $item->id);
        $this->assertEquals($tagd->consumer_id, $consumer->id);
    }

    public function testCreateForResale()
    {
        $repo = app(Tagds::class);

        $reseller = $this->aReseller();
        $tagd = $this->aTagd();

        $newTagd = $repo->createForResale(
            $reseller,
            $tagd,
        );

        $this->assertEquals($newTagd->status, (TagdStatus::RESALE)->value);
        $this->assertEquals($newTagd->parent_id, $tagd->id);
        $this->assertEquals($newTagd->reseller_id, $reseller->id);
        $this->assertEquals($newTagd->item_id, $tagd->item_id);
    }
}
