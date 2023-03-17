<?php

namespace Tagd\Core\Repositories\Interfaces\Items;

use Tagd\Core\Models\Item\Item as Model;
use Tagd\Core\Support\Repository\Interfaces\Repository;

interface Items extends Repository
{
    /**
     * Creates an Item for a Consumer
     *
     * @param  string  $email
     * @param  string  $transactionId
     * @param  string  $retailerId
     * @param  array  $details
     * @return Model
     */
    public function createForConsumer(
        string $email,
        string $transactionId,
        string $retailerId,
        array $details
    ): Model;
}
