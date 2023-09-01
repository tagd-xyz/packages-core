<?php

namespace Tagd\Core\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Tagd\Core\Models\Item\Tagd;
use Tagd\Core\Models\Item\TagdStatus;

class UpdateTagdTimeToTransferStats implements ShouldQueue
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
     * This Job updates the time to transfer stats for a tagd and its parents
     *
     * @return void
     */
    public function handle()
    {
        $tagd = $this->tagd;

        // Only care for transferred tagds that belongs to consumers
        if (
            TagdStatus::TRANSFERRED == $tagd->status
            && ! is_null($tagd->consumer_id)
        ) {
            //build list of children that belong to a consumer
            $nodes = $tagd->buildChildrenCollection(
                function ($node) {
                    return ! is_null($node->consumer_id);
                }
            );

            // active node is last in the tree
            $activeNode = $nodes->last();

            // build array, one ttt for each node
            $ttt = [];
            foreach ($nodes as $index => $node) {
                if ($node->id == $activeNode->id) {
                    continue;
                }

                $next = $nodes[$index + 1] ?? null;
                $prev = $nodes[$index - 1] ?? null;

                if ($next) {
                    $order = 'n' . $index + 1;
                    $ttt[$order] = $next->created_at->diffInMinutes($tagd->created_at);
                }
            }

            // update the stats
            $tagd->update([
                'stats' => [
                    ...(array) $tagd->stats,
                    'ttt' => $ttt,
                ],
            ]);

            //update parent if it is a resale
            if (
                ! is_null($tagd->parent_id)
                && ! is_null($tagd->parent->reseller_id)
            ) {
                $tagd->parent->update([
                    'stats' => [
                        ...(array) $tagd->parent->stats,
                        'ttt' => $ttt,
                    ],
                ]);
            }
        }

        // traverse up the tree
        if (! $tagd->is_root) {
            self::dispatch($tagd->parent);
        }
    }
}
