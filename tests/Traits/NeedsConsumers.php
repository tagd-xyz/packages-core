<?php

namespace Tagd\Core\Tests\Traits;

use Tagd\Core\Models\Actor\Consumer;

trait NeedsConsumers
{
    /**
     * Creates a consumer
     */
    protected function aConsumer(): Consumer
    {
        return Consumer::factory()
            ->create();
    }
}
