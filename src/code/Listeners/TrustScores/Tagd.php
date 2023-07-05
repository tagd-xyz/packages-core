<?php

namespace Tagd\Core\Listeners\TrustScores;

use Illuminate\Events\Dispatcher;
use Tagd\Core\Events\Items\Tagd\StatusUpdated;
use Tagd\Core\Jobs\UpdateTagdTrustScore;
use Tagd\Core\Models\Item\TagdStatus;

class Tagd
{
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
            StatusUpdated::class => 'onStatusUpdated',
        ];
    }
}
