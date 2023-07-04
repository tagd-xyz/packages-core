<?php

namespace Tagd\Core\Tests\Traits;

use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\User\User;

trait NeedsRetailers
{
    /**
     * Creates a retailer
     */
    protected function aRetailer(): Retailer
    {
        return Retailer::factory()
            ->create();
    }

    /**
     * Acts as a retailer
     *
     * @param  null  $guard
     * @return $this
     */
    protected function actingAsARetailer(Retailer $retailer = null, $guard = 'api'): static
    {
        if (is_null($retailer)) {
            $retailer = $this->aRetailer();
        }

        $user = User::factory()
            ->firebase(config('services.firebase.tenant_id_retailers'))
            ->create();
        $user->startActingAs($retailer);

        return $this->actingAs($user, $guard);
    }
}
