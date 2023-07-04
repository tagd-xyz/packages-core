<?php

namespace Tagd\Core\Repositories\Interfaces\Items;

use Tagd\Core\Models\Actor\Consumer as ConsumerModel;
use Tagd\Core\Models\Item\Tagd as Model;
use Tagd\Core\Support\Repository\Interfaces\Repository;

interface Tagds extends Repository
{
    /**
     * Sets tags as available for resale
     */
    public function setAsAvailableForResale(
        Model $tagd
    ): Model;

    /**
     * Confirm a tagd
     */
    public function confirm(
        Model $tagd,
        ConsumerModel $consumer
    ): Model;

    /**
     * Cancel a tagd
     */
    public function cancel(
        Model $tagd
    ): Model;
}
