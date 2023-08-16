<?php

namespace Tagd\Core\Services\Interfaces;

use Tagd\Core\Models\Item\Item as Item;

interface RetailerSales
{
    /**
     * Process a sale made by a retailer
     */
    public function processRetailerSale(
        string $retailerId,
        string $consumerEmail,
        string $transactionId,
        array $price,
        array $itemDetails,
        array $imageUploads
    ): Item;
}
