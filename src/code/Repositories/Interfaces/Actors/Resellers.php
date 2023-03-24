<?php

namespace Tagd\Core\Repositories\Interfaces\Actors;

use Tagd\Core\Models\Actor\Reseller as Model;
use Tagd\Core\Support\Repository\Interfaces\Repository;

interface Resellers extends Repository
{
    /**
     * Asserts a reseller exists
     *
     * @param  mixed  $email
     * @param  string  $name
     * @return Model
     */
    public function assertExists($email, $name = null): Model;
}
