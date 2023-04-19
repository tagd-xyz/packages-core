<?php

namespace Tagd\Core\Repositories\Interfaces\Items;

use Tagd\Core\Models\Item\Stock as Model;
use Tagd\Core\Support\Repository\Interfaces\Repository;

interface Stock extends Repository
{
    /**
     * Update stock images
     */
    public function updateImages(string $stockId, array $imageUploads): Model;
}
