<?php

namespace Tagd\Core\Repositories\Actors;

use Tagd\Core\Models\Actor\Consumer as Model;
use Tagd\Core\Repositories\Interfaces\Actors\Consumers as ConsumersInterface;
use Tagd\Core\Support\Repository\Repository;

class Consumers extends Repository implements ConsumersInterface
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
     * Asserts a consumer exists
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

    /**
     * Finds a consumer by email
     *
     * @param  mixed  $email
     */
    public function findByEmail($email): Model
    {
        return Model::where('email', $email)->firstOrFail();
    }
}
