<?php

namespace Tagd\Core\Events\Resales\AccessRequest;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Tagd\Core\Models\Resale\AccessRequest;

class Created
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $accessRequest;

    /**
     * Create a new event instance.
     *
     * @param  AccessRequest  $accessRequest
     * @return void
     */
    public function __construct(
        AccessRequest $accessRequest,
    ) {
        $this->accessRequest = $accessRequest;
    }
}
