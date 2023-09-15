<?php

namespace Tagd\Core\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Tagd\Core\Models\Item\Tagd;
use Tagd\Core\Models\Item\TagdStatus;

class UpdateTagdAvgResaleStats implements ShouldQueue
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
     * This Job updates the average resale stats for a tagd and its parents
     *
     * @return void
     */
    public function handle()
    {
        $tagd = $this->tagd;

        // Only care for transferred tagds that belong to consumers
        if (
            $tagd->status == TagdStatus::TRANSFERRED
            && ! is_null($tagd->consumer_id)
        ) {
            // build list of parents
            $parents = $tagd->buildParentCollection(
                function ($node) {
                    return ! is_null($node->consumer_id);
                }
            );

            if ($parents->count() > 0) {
                $parentPrice = $parents->first()->meta['price'] ?? null;
                $price = $tagd->meta['price'] ?? null;

                if ($parentPrice['currency'] && $price['currency']) {
                    $diffPerc = round(
                        100 * ($price['amount'] - $parentPrice['amount']) / $parentPrice['amount'], 2
                    );
                    $tagd->update([
                        'stats' => [
                            ...(array) $tagd->stats,
                            'avgResaleDiffPerc' => $diffPerc,
                        ],
                    ]);
                }
            }

            //update parent if it is a resale
            if (! is_null($tagd->parent_id)) {
                $tagd->parent->update([
                    'stats' => [
                        ...(array) $tagd->parent->stats,
                        'avgResaleDiffPerc' => $diffPerc,
                    ],
                ]);
            }
        }
    }

    /**
     * Find the active child of a transferred tagd
     */
    private function findActiveDescendant(Tagd $tagd = null): ?Tagd
    {
        foreach ($tagd->children as $child) {
            if ($child->status == TagdStatus::ACTIVE) {
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
        if ($tagd->status == TagdStatus::TRANSFERRED) {
            foreach ($tagd->children as $child) {
                dd($child);
                if (
                    $child->status == TagdStatus::TRANSFERRED
                    && ! is_null($child->reseller_id)
                ) {
                    foreach ($child->children as $grandChild) {
                        if (
                            $grandChild->status == TagdStatus::TRANSFERRED
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
