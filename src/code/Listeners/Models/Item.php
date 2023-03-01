<?php

namespace Tagd\Core\Listeners\Models;

use Illuminate\Events\Dispatcher;
use Tagd\Core\Events\Items\Item\Created;
use Tagd\Core\Repositories\Interfaces\Actors\Consumers as ConsumersRepo;
use Tagd\Core\Repositories\Items\Tagds as TagdsRepo;

class Item
{
    /**
     * on Item created
     *
     * @param  Created  $event
     * @return void
     */
    public function onCreated(Created $event)
    {
        $consumersRepo = app(ConsumersRepo::class);
        $consumer = $consumersRepo->assertExists($event->consumerEmail);

        $tagdsRepo = app(TagdsRepo::class);
        $tagdsRepo->createFor($event->item, $consumer, $event->transactionId);
    }

    /**
     * Subscribe
     *
     * @param  Dispatcher  $events
     * @return array
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            Created::class => 'onCreated',
        ];
    }
}
