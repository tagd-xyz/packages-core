<?php

namespace Tagd\Core\Listeners\Models;

use Illuminate\Events\Dispatcher;
use Tagd\Core\Events\Items\Tagd\Created;
use Tagd\Core\Notifications\Consumers\TagdCreated as TagdCreatedNotification;

class Tagd
{
    /**
     * on Tagd created
     *
     * @return void
     */
    public function onCreated(Created $event)
    {
        $tagd = $event->tagd;

        if ($tagd->is_root) {
            // notify the consumer
            $tagd->consumer->notify(new TagdCreatedNotification(
                $tagd
            ));
        }
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
