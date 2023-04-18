<?php

namespace Tagd\Core\Repositories\Interfaces\Uploads;

use Tagd\Core\Models\Upload\Upload as Model;
use Tagd\Core\Support\Repository\Interfaces\Repository;

interface Stocks extends Repository
{
    /**
     * Creates a new upload for an image
     */
    public function image(string $stockId, string $fileName): Model;
}
