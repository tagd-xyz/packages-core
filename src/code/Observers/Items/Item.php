<?php

namespace Tagd\Core\Observers\Items;

use Tagd\Core\Events\Items\Item\Created;
use Tagd\Core\Models\Item\Item as ItemModel;

class Item
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the Item "created" event.
     *
     * @return void
     */
    public function created(ItemModel $item)
    {
        Created::dispatch($item);

        // $tagd = $item->rootTagd;

        // \Log::info('item_id' . $item->id);
        // \Log::info('tagd_id' . $tagd->id);
        // \Log::info('consumer_id: ' . $tagd->consumer_id);

        // Created::dispatch(
        //     $item,
        //     $tagd->consumer->email,
        //     $tagd->meta['transaction'] ?? ''
        // );
    }
}
