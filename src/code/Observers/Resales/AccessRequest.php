<?php

namespace Tagd\Core\Observers\Resales;

use Tagd\Core\Events\Resales\AccessRequest\Created;
use Tagd\Core\Models\Resale\AccessRequest as AccessRequestModel;

class AccessRequest
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the AccessRequest "created" event.
     *
     * @param  AccessRequestModel  $accessRequest
     * @return void
     */
    public function created(AccessRequestModel $accessRequest)
    {
        Created::dispatch($accessRequest);
    }
}
