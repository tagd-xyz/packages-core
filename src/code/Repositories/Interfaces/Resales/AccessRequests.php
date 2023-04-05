<?php

namespace Tagd\Core\Repositories\Interfaces\Resales;

use Tagd\Core\Models\Resale\AccessRequest as Model;
use Tagd\Core\Support\Repository\Interfaces\Repository;

interface AccessRequests extends Repository
{
    /**
     * Rejects an access request
     *
     * @param  Model  $accessRequest
     * @return Model
     */
    public function reject(Model $accessRequest): Model;

    /**
     * Approves an access request
     *
     * @param  Model  $accessRequest
     * @return Model
     */
    public function approve(Model $accessRequest): Model;
}
