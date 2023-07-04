<?php

namespace Tagd\Core\Services\RetailerSales;

use Illuminate\Support\Facades\DB;
use Tagd\Core\Models\Item\Item as Item;
use Tagd\Core\Repositories\Interfaces\Actors\Consumers as ConsumersRepo;
use Tagd\Core\Repositories\Interfaces\Items\Items as ItemsRepo;
use Tagd\Core\Repositories\Interfaces\Items\Tagds as TagdsRepo;

trait ProcessRetailerSale
{
    /**
     * Process a sale made by a retailer
     */
    public function processRetailerSale(
        string $retailerId,
        string $consumerEmail,
        string $transactionId,
        array $itemDetails,
        array $imageUploads,
    ): Item {

        return DB::transaction(function () use (
            $retailerId,
            $consumerEmail,
            $transactionId,
            $itemDetails,
            $imageUploads
        ) {
            $itemsRepo = app(ItemsRepo::class);
            $consumersRepo = app(ConsumersRepo::class);
            $tagdsRepo = app(TagdsRepo::class);

            // Create item
            $item = $itemsRepo->create([
                ...$itemDetails,
                'retailer_id' => $retailerId,
            ]);
            $itemsRepo->updateImages($item->id, $imageUploads);

            // Make sure the consumer exists
            $consumer = $consumersRepo
                ->assertExists($consumerEmail);

            // Create Tagd
            $tagd = $tagdsRepo->create([
                'item_id' => $item->id,
                'consumer_id' => $consumer->id,
                'meta' => [
                    'transaction' => $transactionId,
                ],
            ]);

            // Automatically set the newly created Tagd as "active"
            $tagd->activate();

            // Return newly created item
            return $item;
        });
    }
}
