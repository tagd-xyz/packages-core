<?php

namespace Tagd\Core\Repositories\Interfaces\Items;

use Tagd\Core\Models\Item\Item as Model;
use Tagd\Core\Support\Repository\Interfaces\Repository;

interface Items extends Repository
{
    /**
     * Creates an Item for a Consumer
     */
    public function createForConsumer(
        string $email,
        string $transactionId,
        string $retailerId,
        array $details
    ): Model;
}
