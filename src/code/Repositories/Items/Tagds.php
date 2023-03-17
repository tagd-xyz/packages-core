<?php

namespace Tagd\Core\Repositories\Items;

use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
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
     *
     * @param  Model  $model
     */
    public function __construct(Model $model)
    {
        parent::__construct($model);
    }

    /**
     * Creates tagd for an item recently created
     *
     * @param  ItemModel  $item
     * @param  ConsumerModel  $consumer
     * @param  string  $transactionId
     * @return Model
     */
    public function createFor(
        ItemModel $item,
        ConsumerModel $consumer,
        string $transactionId
    ): Model {
        return $this->create([
            'item_id' => $item->id,
            'consumer_id' => $consumer->id,
            'meta' => [
                'transaction' => $transactionId,
            ],
        ]);
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

        $tagd = $this->create([
            'parent_id' => $parentTagd->id,
            'item_id' => $parentTagd->item_id,
            'reseller_id' => $reseller->id,
            'status' => TagdStatus::RESALE,
            'status_at' => Carbon::now(),
        ]);
        $tagd->refresh();

        return $tagd;
    }

    /**
     * Sets tags as active
     *
     * @param  Model  $tagd
     * @return Model
     */
    public function activate(
        Model $tagd
    ): Model {
        $tagd->activate();

        return $tagd;
    }

    /**
     * Sets tags as available for resale
     *
     * @param  Model  $tagd
     * @return Model
     */
    public function setAsAvailableForResale(
        Model $tagd,
        bool $enabled = true
    ): Model {
        $tagd->enableForResale($enabled);

        return $tagd;
    }

    /**
     * Confirm a tagd
     *
     * @param  Model  $tagd
     * @param  ConsumerModel  $consumer
     * @return Model
     */
    public function confirm(
        Model $tagd,
        ConsumerModel $consumer
    ): Model {
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
            'status' => TagdStatus::ACTIVE,
            'status_at' => Carbon::now(),
        ]);

        return $newTagd;
    }

    /**
     * Cancel a tagd
     *
     * @param  Model  $tagd
     * @return Model
     */
    public function cancel(
        Model $tagd,
    ): Model {
        $tagd->cancel();
        $tagd->refresh();

        return $tagd;
    }
}
