<?php

namespace Tagd\Core\Repositories\Interfaces\Actors;

use Tagd\Core\Models\Actor\Admin as Model;
use Tagd\Core\Support\Repository\Interfaces\Repository;

interface Admins extends Repository
{
    /**
     * Asserts an admin exists
     *
     * @param  mixed  $email
     * @param  string  $name
     */
    public function assertExists($email, $name = null): Model;
}
