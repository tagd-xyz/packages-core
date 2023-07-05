<?php

namespace Tagd\Core\Repositories\Items;

use Illuminate\Support\Facades\DB;
use Tagd\Core\Models\Item\Tagd as Model;
use Tagd\Core\Repositories\Interfaces\Items\Tagds as TagdsInterface;
use Tagd\Core\Support\Repository\Repository;

class Tagds extends Repository implements TagdsInterface
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
     * Sets tags as active
     */
    public function activate(
        Model $tagd
    ): Model {
        return DB::transaction(function () use (
            $tagd
        ) {
            $tagd->activate();

            return $tagd;
        }, 5);
    }

    /**
     * Sets tags as available for resale
     */
    public function setAsAvailableForResale(
        Model $tagd,
        bool $enabled = true
    ): Model {
        return DB::transaction(function () use (
            $tagd,
            $enabled
        ) {
            $tagd->enableForResale($enabled);

            return $tagd;
        }, 5);
    }

    /**
     * Cancel a tagd
     */
    public function cancel(
        Model $tagd,
    ): Model {
        return DB::transaction(function () use ($tagd) {
            $tagd->cancel();
            $tagd->refresh();

            return $tagd;
        }, 5);
    }
}
