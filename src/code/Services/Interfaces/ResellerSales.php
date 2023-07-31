<?php

namespace Tagd\Core\Services\Interfaces;

use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Item\Tagd;

interface ResellerSales
{
    /**
     * Lists an item for resale
     */
    public function startResellerSale(
        Reseller $reseller,
        Tagd $parentTagd
    ): Tagd;
}
