<?php

namespace Tagd\Core\Repositories\Interfaces\Uploads;

use Tagd\Core\Models\Upload\Upload as Model;
use Tagd\Core\Support\Repository\Interfaces\Repository;

interface Items extends Repository
{
    /**
     * Creates a new upload for an image
     */
    public function image(string $itemId, string $fileName): Model;
}
