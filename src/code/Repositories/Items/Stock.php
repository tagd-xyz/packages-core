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

    /**
     * Update stock images
     */
    public function updateImages(string $stockId, array $imageUploads): Model
    {
        $stock = Model::find($stockId);

        foreach ($imageUploads as $uploadId) {
            $stock->images()->updateOrCreate([
                'upload_id' => $uploadId,
            ]);
        }

        $stock->load('images');

        return $stock;
    }
}
