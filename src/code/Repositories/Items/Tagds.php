<?php

namespace Tagd\Core\Repositories\Items;

use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\DB;
use Tagd\Core\Models\Actor\Consumer as ConsumerModel;
use Tagd\Core\Models\Actor\Reseller as ResellerModel;
use Tagd\Core\Models\Item\Item as ItemModel;
use Tagd\Core\Models\Item\Tagd as Model;
use Tagd\Core\Models\Item\TagdStatus;
use Tagd\Core\Repositories\Interfaces\Items\Tagds as TagdsInterface;
use Tagd\Core\Support\Repository\Repository;

class Tagds extends Repository implements TagdsInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Constructor.
     */
    public function __construct(Model $model)
    {
        parent::__construct($model);
    }

    /**
     * Creates tagd for an item recently created
     */
    public function createFor(
        ItemModel $item,
        ConsumerModel $consumer,
        string $transactionId
    ): Model {
        return DB::transaction(function () use (
            $item, $consumer, $transactionId
        ) {
            return $this->create([
                'item_id' => $item->id,
                'consumer_id' => $consumer->id,
                'meta' => [
                    'transaction' => $transactionId,
                ],
            ]);
        }, 5);
    }

    public function createForResale(
        ResellerModel $reseller,
        Model $parentTagd
    ): Model {
        // if (
        //     $parentTagd->isTransferred ||
        //     $parentTagd->isExpired ||
        //     ! $parentTagd->isActive
        // ) {
        //     throw new AuthenticationException('Action not allowed');
        // }

        return DB::transaction(function () use (
            $reseller, $parentTagd
        ) {
            $tagd = $this->create([
                'parent_id' => $parentTagd->id,
                'item_id' => $parentTagd->item_id,
                'reseller_id' => $reseller->id,
                'trust' => $parentTagd->trust,
                'status' => TagdStatus::RESALE,
                'status_at' => Carbon::now(),
            ]);
            $tagd->refresh();

            return $tagd;
        }, 5);
    }

    /**
     * Sets tags as active
     */
    public function activate(
        Model $tagd
    ): Model {
        return DB::transaction(function () use (
            $tagd
        ) {
            $tagd->activate();

            return $tagd;
        }, 5);
    }

    /**
     * Sets tags as available for resale
     */
    public function setAsAvailableForResale(
        Model $tagd,
        bool $enabled = true
    ): Model {
        return DB::transaction(function () use (
            $tagd, $enabled
        ) {
            $tagd->enableForResale($enabled);

            return $tagd;
        }, 5);
    }

    /**
     * Confirm a tagd
     */
    public function confirm(
        Model $tagd,
        ConsumerModel $consumer
    ): Model {
        return DB::transaction(function () use (
            $tagd, $consumer
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

            $newTagd = $this->create([
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

    /**
     * Cancel a tagd
     */
    public function cancel(
        Model $tagd,
    ): Model {
        return DB::transaction(function () use ($tagd) {
            $tagd->cancel();
            $tagd->refresh();

            return $tagd;
        }, 5);
    }
}
