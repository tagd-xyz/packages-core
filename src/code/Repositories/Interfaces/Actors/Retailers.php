<?php

namespace Tagd\Core\Repositories\Interfaces\Actors;

use Tagd\Core\Models\Actor\Retailer as Model;
use Tagd\Core\Support\Repository\Interfaces\Repository;

interface Retailers extends Repository
{
    /**
     * Asserts a retailer exists
     *
     * @param  mixed  $authId
     * @param  string  $name
     * @return Model
     */
    public function assertExists($authId, $name = null): Model;
}
