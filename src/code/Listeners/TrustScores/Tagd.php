<?php

namespace Tagd\Core\Listeners\TrustScores;

use Illuminate\Events\Dispatcher;
use Tagd\Core\Events\Items\Tagd\Created;
use Tagd\Core\Events\Items\Tagd\StatusUpdated;
use Tagd\Core\Jobs\UpdateTagdTrustScore;
use Tagd\Core\Models\Item\TagdStatus;

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

        UpdateTagdTrustScore::dispatch($tagd);
    }

    /**
     * on Tagd status updated
     *
     * @return void
     */
    public function onStatusUpdated(StatusUpdated $event)
    {
        $tagd = $event->tagd;

        switch ($tagd->status) {
            case TagdStatus::RESALE:
                UpdateTagdTrustScore::dispatch($tagd);
                break;
        }
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
