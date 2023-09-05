<?php

namespace Tagd\Core\Listeners\Models;

use Illuminate\Events\Dispatcher;
use Tagd\Core\Events\Items\Tagd\Created;
use Tagd\Core\Events\Items\Tagd\StatusUpdated;
use Tagd\Core\Jobs\UpdateTagdAvgResaleStats;
use Tagd\Core\Jobs\UpdateTagdCountStats;
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

        UpdateTagdTimeToTransferStats::dispatch($tagd);
        UpdateTagdCountStats::dispatch($tagd);
        UpdateTagdAvgResaleStats::dispatch($tagd);
    }

    /**
     * on Tagd status updated
     *
     * @return void
     */
    public function onStatusUpdated(StatusUpdated $event)
    {
        $tagd = $event->tagd;

        // do nothing
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
