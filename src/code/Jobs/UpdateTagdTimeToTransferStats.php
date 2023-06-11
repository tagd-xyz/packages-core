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
     * @return void
     */
    public function handle()
    {
        $tagd = $this->tagd;

        if (
            TagdStatus::TRANSFERRED == $tagd->status
            && ! is_null($tagd->consumer_id)
        ) {
            //build list of nodes
            $nodes = $tagd->buildChildrenCollection(
                function ($node) {
                    return ! is_null($node->consumer_id);
                }
            );

            $activeNode = $nodes->last();

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

            $stats = $tagd->stats;
            $stats['ttt'] = $ttt;

            $tagd->update([
                'stats' => $stats,
            ]);

            //update parent if it is a resale
            if (! is_null($tagd->parent_id)) {
                $tagd->parent->update([
                    'stats' => $stats,
                ]);
            }
        }

        if (! $tagd->is_root) {
            self::dispatch($tagd->parent);
        }
    }

    /**
     * Find the active child of a transferred tagd
     */
    private function findActiveDescendant(Tagd $tagd = null): ?Tagd
    {
        foreach ($tagd->children as $child) {
            if (TagdStatus::ACTIVE == $child->status) {
                return $child;
            } else {
                return $this->findActiveDescendant($child);
            }
        }

        return null;
    }

    /**
     * Find the next transferred node (to a consumer)
     */
    private function findNextTransfer(Tagd $tagd): ?Tagd
    {
        if (TagdStatus::TRANSFERRED == $tagd->status) {
            foreach ($tagd->children as $child) {
                dd($child);
                if (
                    TagdStatus::TRANSFERRED == $child->status
                    && ! is_null($child->reseller_id)
                ) {
                    foreach ($child->children as $grandChild) {
                        if (
                            TagdStatus::TRANSFERRED == $grandChild->status
                            && ! is_null($grandChild->consumer_id)
                        ) {
                            return $grandChild;
                        }
                    }
                }
            }
        }

        return null;
    }
}
