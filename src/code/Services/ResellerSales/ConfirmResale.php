<?php

namespace Tagd\Core\Services\ResellerSales;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\Item\Tagd;
use Tagd\Core\Models\Item\TagdStatus;
use Tagd\Core\Repositories\Interfaces\Items\Tagds as TagdsRepo;

trait ConfirmResale
{
    /**
     * Confirms a resale
     */
    public function confirmResale(
        Tagd $tagd,
        Consumer $consumer
    ): Tagd {
        return DB::transaction(function () use (
            $tagd,
            $consumer
        ) {
            $tagd->transfer();
            $tagd->parent->transfer();

            // expire siblings
            $activeSiblings = $tagd->parent->children
                ->filter(function ($child) use ($tagd) {
                    return
                        $child->id != $tagd->id
                        && TagdStatus::RESALE == $child->status;
                });

            foreach ($activeSiblings as $sibling) {
                $sibling->expire();
            }

            // create new tagd
            $tagdsRepo = app(TagdsRepo::class);
            $newTagd = $tagdsRepo->create([
                'parent_id' => $tagd->id,
                'item_id' => $tagd->item_id,
                'consumer_id' => $consumer->id,
                'trust' => $tagd->trust,
                'status' => TagdStatus::ACTIVE,
                'status_at' => Carbon::now(),
            ]);

            return $newTagd;
        }, 5);
    }
}
