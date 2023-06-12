<?php

namespace Tagd\Core\Observers\Items;

use Tagd\Core\Events\Items\Tagd\Created;
use Tagd\Core\Events\Items\Tagd\StatusUpdated;
use Tagd\Core\Models\Item\Tagd as TagdModel;

class Tagd
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the Tagd "created" event.

     *
     * @return void
     */
    public function created(TagdModel $tagd)
    {
        Created::dispatch($tagd);
    }

    /**
     * Handle the Tagd "updated" event
     *
     * @return void
     */
    public function updated(TagdModel $tagd)
    {
        if ($tagd->wasChanged('status')) {
            // if ($tagd->isDirty('status')) {
            StatusUpdated::dispatch($tagd);
        }
    }
}
