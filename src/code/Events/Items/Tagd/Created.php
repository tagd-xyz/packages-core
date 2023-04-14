<?php

namespace Tagd\Core\Events\Items\Tagd;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Tagd\Core\Models\Item\Tagd;

class Created
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tagd;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        Tagd $tagd
    ) {
        $this->tagd = $tagd;
    }
}
