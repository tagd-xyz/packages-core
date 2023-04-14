<?php

namespace Tagd\Core\Observers\Items;

use Tagd\Core\Events\Items\Tagd\Created;
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
     * Handle the Item "created" event.
     *
     * @return void
     */
    public function created(TagdModel $tagd)
    {
        Created::dispatch($tagd);
    }
}
