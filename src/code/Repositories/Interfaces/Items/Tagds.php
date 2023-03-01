<?php

namespace Tagd\Core\Repositories\Interfaces\Items;

use Tagd\Core\Models\Actor\Consumer as ConsumerModel;
use Tagd\Core\Models\Item\Item as ItemModel;
use Tagd\Core\Models\Item\Tagd as Model;
use Tagd\Core\Support\Repository\Interfaces\Repository;

interface Tagds extends Repository
{
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
    ): Model;
}
