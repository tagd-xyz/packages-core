<?php

namespace Tagd\Core\Listeners\Models;

use Illuminate\Events\Dispatcher;
use Tagd\Core\Events\Resales\AccessRequest\Created;
use Tagd\Core\Notifications\Consumers\AccessRequested as AccessRequestedNotification;

class AccessRequest
{
    /**
     * on Item created
     *
     * @param  Created  $event
     * @return void
     */
    public function onCreated(Created $event)
    {
        // A Reseller has just request access to a consumer items

        // notify the consumer
        $event->accessRequest->consumer->notify(new AccessRequestedNotification(
            $event->accessRequest->reseller,
            $event->accessRequest->id
        ));
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
