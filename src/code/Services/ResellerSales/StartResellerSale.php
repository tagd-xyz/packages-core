<?php

namespace Tagd\Core\Services\ResellerSales;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Item\Tagd;
use Tagd\Core\Models\Item\TagdStatus;
use Tagd\Core\Repositories\Interfaces\Items\Tagds as TagdsRepo;

trait StartResellerSale
{
    /**
     * Lists an item for resale
     */
    public function startResellerSale(
        Reseller $reseller,
        Tagd $parentTagd
    ): Tagd {

        return DB::transaction(function () use (
            $reseller,
            $parentTagd
        ) {
            $tagdsRepo = app(TagdsRepo::class);

            $tagd = $tagdsRepo->create([
                'parent_id' => $parentTagd->id,
                'item_id' => $parentTagd->item_id,
                'reseller_id' => $reseller->id,
                'trust' => $parentTagd->trust,
                'status' => TagdStatus::RESALE,
                'status_at' => Carbon::now(),
            ]);

            // Return newly created tagd
            return $tagd;
        });
    }
}
