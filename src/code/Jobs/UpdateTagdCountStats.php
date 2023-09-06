<?php

namespace Tagd\Core\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Tagd\Core\Models\Item\Tagd;
use Tagd\Core\Models\Item\TagdStatus;

class UpdateTagdCountStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The tagd instance.
     *
     * @var Tagd
     */
    public $tagd;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Tagd $tagd)
    {
        $this->tagd = $tagd;
    }

    /**
     * Execute the job.
     *
     * This job calculates the 'count' stats for a tag and its parents
     *
     * @return void
     */
    public function handle()
    {
        $parent = $this->tagd->parent;

        if ($parent) {
            $count = [];

            // count per status
            foreach (TagdStatus::cases() as $status) {
                $count[$status->value] =
                    $parent->countAllChildren(function ($child) use ($status) {
                        return $status->value == $child->status->value;
                    });
            }

            // count self
            $count[$parent->status->value]++;

            // count per status (split by actor) - only transferred
            foreach (TagdStatus::cases() as $status) {
                if ($status == TagdStatus::TRANSFERRED) {
                    $count[$status->value . '_consumer'] =
                        $parent->countAllChildren(function ($child) use ($status) {
                            return ! is_null($child->consumer_id)
                                && $status->value == $child->status->value;
                        });
                }
            }

            // count self
            if (
                $parent->status == TagdStatus::TRANSFERRED
                && ! is_null($parent->consumer_id)
            ) {
                $count[$parent->status->value . '_consumer']++;
            }

            // update parent and dispatch recursive job
            $parent->update([
                'stats' => [
                    ...(array) $parent->stats,
                    'count' => $count,
                ],
            ]);

            self::dispatch($parent);
        }
    }
}
