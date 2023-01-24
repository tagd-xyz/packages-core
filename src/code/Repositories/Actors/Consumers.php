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
     *
     * @param  Model  $model
     */
    public function __construct(Model $model)
    {
        parent::__construct($model);
    }
}
