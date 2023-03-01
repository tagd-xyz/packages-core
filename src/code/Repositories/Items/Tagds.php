<?php

namespace Tagd\Core\Repositories\Items;

use Illuminate\Auth\AuthenticationException;
use Tagd\Core\Models\Actor\Consumer as ConsumerModel;
use Tagd\Core\Models\Actor\Reseller as ResellerModel;
use Tagd\Core\Models\Item\Item as ItemModel;
use Tagd\Core\Models\Item\Tagd as Model;
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
        string $parentTagdSlug
    ): Model {
        $parentTagd = Model::where('slug', $parentTagdSlug)->firstOrFail();

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
        ]);
        // $tagd->activate();
        $tagd->refresh();

        return $tagd;
    }
}
