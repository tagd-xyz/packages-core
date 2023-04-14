<?php

namespace Tagd\Core\Listeners\Models;

use Illuminate\Events\Dispatcher;
use Tagd\Core\Events\Items\Item\Created;

class Item
{
    /**
     * on Item created
     *
     * @return void
     */
    public function onCreated(Created $event)
    {
    }

    /**
     * Subscribe
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            Created::class => 'onCreated',
        ];
    }
}
