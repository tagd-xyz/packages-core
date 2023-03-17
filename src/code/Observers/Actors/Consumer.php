<?php

namespace Tagd\Core\Observers\Actors;

use Tagd\Core\Events\Actors\Consumer\Created;
use Tagd\Core\Models\Actor\Consumer as ConsumerModel;

class Consumer
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the Consumer "created" event.
     *
     * @param  ConsumerModel  $consumer
     * @return void
     */
    public function created(ConsumerModel $consumer)
    {
        Created::dispatch($consumer);
    }
}
