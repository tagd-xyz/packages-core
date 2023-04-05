<?php

namespace Tagd\Core\Events\Actors\Consumer;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Tagd\Core\Models\Actor\Consumer;

class Created
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $consumer;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        Consumer $consumer,
    ) {
        $this->consumer = $consumer;
    }
}
