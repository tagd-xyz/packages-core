<?php

namespace Tagd\Core\Events\Items\Item;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Tagd\Core\Models\Item\Item;

class Created
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $item;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        Item $item
    ) {
        $this->item = $item;
    }
}
