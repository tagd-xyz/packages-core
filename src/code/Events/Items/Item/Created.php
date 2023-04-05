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

    public $consumerEmail;

    public $transactionId;

    /**
     * Create a new event instance.
     *
     * @param  string  $consumerEmail
     * @return void
     */
    public function __construct(
        Item $item,
        string $consumerEmail = null,
        string $transactionId
    ) {
        $this->item = $item;
        $this->consumerEmail = $consumerEmail;
        $this->transactionId = $transactionId;
    }
}
