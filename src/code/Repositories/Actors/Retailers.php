<?php

namespace Tagd\Core\Repositories\Actors;

use Tagd\Core\Models\Actor\Retailer as Model;
use Tagd\Core\Repositories\Interfaces\Actors\Retailers as RetailersInterface;
use Tagd\Core\Support\Repository\Repository;

class Retailers extends Repository implements RetailersInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Constructor.
     *
     * @param  Model  $model
     */
    public function __construct(Model $model)
    {
        parent::__construct($model);
    }

    /**
     * Asserts a retailer exists
     *
     * @param  mixed  $email
     * @param  string  $name
     * @return Model
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
