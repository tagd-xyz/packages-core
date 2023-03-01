<?php

namespace Tagd\Core\Repositories\Interfaces\Actors;

use Tagd\Core\Models\Actor\Consumer as Model;
use Tagd\Core\Support\Repository\Interfaces\Repository;

interface Consumers extends Repository
{
    /**
     * Asserts a consumer exists
     *
     * @param  mixed  $email
     * @return Model
     */
    public function assertExists($email): Model;
}
