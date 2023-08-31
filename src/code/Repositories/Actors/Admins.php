<?php

namespace Tagd\Core\Repositories\Actors;

use Tagd\Core\Models\Actor\Admin as Model;
use Tagd\Core\Repositories\Interfaces\Actors\Admins as AdminsInterface;
use Tagd\Core\Support\Repository\Repository;

class Admins extends Repository implements AdminsInterface
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
     * Asserts an admin exists
     *
     * @param  mixed  $email
     * @param  string  $name
     */
    public function assertExists($email, $name = null): Model
    {
        $model = Model::firstOrCreate([
            'email' => $email,
        ], [
            'name' => $name,
        ]);

        return $model;
    }
}
