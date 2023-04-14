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
        array $itemDetails
    ): Item {

        return DB::transaction(function () use (
            $retailerId,
            $consumerEmail,
            $transactionId,
            $itemDetails
        ) {
            $itemsRepo = app(ItemsRepo::class);
            $consumersRepo = app(ConsumersRepo::class);
            $tagdsRepo = app(TagdsRepo::class);

            // Create item
            $item = $itemsRepo->create([
                ...$itemDetails,
                'retailer_id' => $retailerId,
            ]);

            // Make sure the consumer exists
            $consumer = $consumersRepo
                ->assertExists($consumerEmail);

            // Create Tagd
            $tagd = $tagdsRepo->createFor(
                $item,
                $consumer,
                $transactionId
            );

            // Automatically set the newly created Tagd as "active"
            $tagd->activate();

            // Return newly created item
            return $item;
        });
    }
}
