<?php

namespace Tagd\Core\Listeners\Models;

use Illuminate\Events\Dispatcher;
use Tagd\Core\Events\Items\Tagd\Created;
use Tagd\Core\Events\Items\Tagd\StatusUpdated;
use Tagd\Core\Jobs\UpdateTagdAncestorsStats;
use Tagd\Core\Jobs\UpdateTagdTimeToTransferStats;
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

        UpdateTagdAncestorsStats::dispatch($tagd);
        UpdateTagdTimeToTransferStats::dispatch($tagd);
    }

    /**
     * on Tagd status updated
     *
     * @return void
     */
    public function onStatusUpdated(StatusUpdated $event)
    {
        $tagd = $event->tagd;

        UpdateTagdAncestorsStats::dispatch($tagd);
        // UpdateTagdTimeToTransferStats::dispatch($tagd);
    }

    /**
     * Subscribe
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            Created::class => 'onCreated',
            StatusUpdated::class => 'onStatusUpdated',
        ];
    }
}
