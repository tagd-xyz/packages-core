<?php

namespace Tagd\Core\Repositories\Items;

use Tagd\Core\Models\Item\Item as Model;
use Tagd\Core\Repositories\Interfaces\Items\Items as ItemsInterface;
use Tagd\Core\Support\Repository\Repository;

class Items extends Repository implements ItemsInterface
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
}
