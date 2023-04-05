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
     * @param  string  $name
     */
    public function assertExists($email, $name = null): Model;

    /**
     * Finds a consumer by email
     *
     * @param  mixed  $email
     */
    public function findByEmail($email): Model;
}
