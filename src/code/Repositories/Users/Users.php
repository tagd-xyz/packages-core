<?php

namespace Tagd\Core\Repositories\Users;

use Illuminate\Database\QueryException;
use Tagd\Core\Models\User\User as Model;
use Tagd\Core\Repositories\Interfaces\Users\Users as UsersInterface;
use Tagd\Core\Support\Repository\Repository;

class Users extends Repository implements UsersInterface
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
     * Creates a user from a Firebase Token
     */
    public function createFromFirebaseToken(object $payload): Model
    {
        try {
            $user = Model::firstOrCreate([
                'firebase_id' => $payload->user_id,
                'firebase_tenant' => $payload->firebase->tenant,
            ], [
                'email' => $payload->email,
                'name' => $payload->name ?? $payload->email,
            ]);
        } catch (QueryException $e) {
            $user = Model::where('firebase_id', $payload->user_id)
                ->where('firebase_tenant', $payload->firebase->tenant)
                ->firstOrFail();
        }

        return $user;
    }

    /**
     * Assert a user is acting as the given actor type
     *
     * @return void
     */
    public function assertIsActingAs(Model $user, string $actorClass)
    {
        try {
            $actor = $actorClass::firstOrCreate([
                'email' => $user->email,
            ], [
                'name' => $user->name,
            ]);
        } catch (QueryException $e) {
            $actor = $actorClass::where('email', $user->email)
                ->firstOrFail();
        }

        try {
            $user->startActingAs($actor);
        } catch (QueryException $e) {
            // empty on purpose
        }
    }
}
