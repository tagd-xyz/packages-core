<?php

namespace Tagd\Core\Repositories\Items;

use Tagd\Core\Events\Items\Item\Created as ItemCreated;
use Tagd\Core\Models\Item\Item as Model;
use Tagd\Core\Repositories\Interfaces\Items\Items as ItemsInterface;
use Tagd\Core\Support\Repository\Repository;

class Items extends Repository implements ItemsInterface
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
     * Creates an Item for a Consumer
     */
    public function createForConsumer(
        string $email,
        string $transactionId,
        string $retailerId,
        array $details
    ): Model {
        $item = $this->create([
            ...$details,
            'retailer_id' => $retailerId,
        ]);

        //TODO: move to somewhere better
        ItemCreated::dispatch($item, $email, $transactionId);

        return $item;
    }
}
