<?php

namespace Tagd\Core\Repositories\Interfaces\Users;

use Tagd\Core\Models\User\User as Model;
use Tagd\Core\Support\Repository\Interfaces\Repository;

interface Users extends Repository
{
    /**
     * Creates a user from a Firebase Token
     */
    public function createFromFirebaseToken(object $payload): Model;

    /**
     * Assert a user is acting as the given actor type
     *
     * @return void
     */
    public function assertIsActingAs(Model $user, string $actorClass);
}
