<?php

namespace Tagd\Core\Repositories\Interfaces\Uploads;

use Tagd\Core\Models\Upload\Upload as Model;
use Tagd\Core\Support\Repository\Interfaces\Repository;

interface Resellers extends Repository
{
    /**
     * Creates a new upload for an avatar
     */
    public function avatar(string $resellerId, string $fileName): Model;
}
