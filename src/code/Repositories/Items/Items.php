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

    /**
     * Update item images
     */
    public function updateImages(string $itemId, array $imageUploads): Model
    {
        $item = Model::find($itemId);

        foreach ($imageUploads as $uploadId) {
            $item->images()->updateOrCreate([
                'upload_id' => $uploadId,
            ]);
        }

        $item->load('images');

        return $item;
    }
}
