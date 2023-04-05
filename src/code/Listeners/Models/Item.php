<?php

namespace Tagd\Core\Listeners\Models;

use Illuminate\Events\Dispatcher;
use Tagd\Core\Events\Items\Item\Created;
use Tagd\Core\Notifications\Consumers\TagdCreated as TagdCreatedNotification;
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
        // A Retailer has just created a new item
        // Make sure the consumer exists, and create its Tagd

        $consumersRepo = app(ConsumersRepo::class);
        $consumer = $consumersRepo->assertExists($event->consumerEmail);

        $tagdsRepo = app(TagdsRepo::class);
        $tagd = $tagdsRepo->createFor($event->item, $consumer, $event->transactionId);

        // notify the consumer
        $consumer->notify(new TagdCreatedNotification(
            $tagd
        ));

        // automatically set the newly created Tagd as "active"
        $tagd->activate();
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
