<?php

namespace Tagd\Core\Tests\Traits;

use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\User\User;

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

    /**
     * Acts as a consumer
     *
     * @return $this
     */
    protected function actingAsAConsumer(Consumer $consumer = null, $guard = 'api'): static
    {
        if (is_null($consumer)) {
            $consumer = $this->aConsumer();
        }

        $user = User::factory()
            ->firebase('consumers')
            ->create();
        $user->startActingAs($consumer);

        return $this->actingAs($user, $guard);
    }
}
