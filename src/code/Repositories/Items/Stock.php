<?php

namespace Tagd\Core\Repositories\Items;

use Tagd\Core\Models\Item\Stock as Model;
use Tagd\Core\Repositories\Interfaces\Items\Stock as StockInterface;
use Tagd\Core\Support\Repository\Repository;

class Stock extends Repository implements StockInterface
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
