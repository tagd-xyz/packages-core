<?php

namespace Tagd\Core\Repositories\Resales;

use Tagd\Core\Models\Resale\AccessRequest as Model;
use Tagd\Core\Repositories\Interfaces\Resales\AccessRequests as AccessRequestsInterface;
use Tagd\Core\Support\Repository\Repository;

class AccessRequests extends Repository implements AccessRequestsInterface
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
     * Rejects an access request
     */
    public function reject(Model $accessRequest): Model
    {
        $accessRequest->reject();

        return $accessRequest;
    }

    /**
     * Approves an access request
     */
    public function approve(Model $accessRequest): Model
    {
        $accessRequest->approve();

        return $accessRequest;
    }
}
