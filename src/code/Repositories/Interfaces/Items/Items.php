<?php

namespace Tagd\Core\Repositories\Interfaces\Items;

use Tagd\Core\Models\Item\Item as Model;
use Tagd\Core\Support\Repository\Interfaces\Repository;

interface Items extends Repository
{
    /**
     * Update item images
     */
    public function updateImages(string $itemId, array $imageUploads): Model;
}
