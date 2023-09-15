<?php

namespace Tagd\Core\Tests\Traits;

use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\User\Role;
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
     * @return $this
     */
    protected function actingAsARetailer(Retailer $retailer = null, $guard = 'api'): static
    {
        if (is_null($retailer)) {
            $retailer = $this->aRetailer();
        }

        $user = User::factory()
            ->firebase('retailers')
            ->create();
        $user->startActingAs($retailer);

        // tenant is injected by the auth provider, according to the bearer token
        $user->tenant = Role::RETAILER;

        return $this->actingAs($user, $guard);
    }
}
