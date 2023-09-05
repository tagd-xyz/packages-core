<?php

namespace Tagd\Core\Tests\Traits;

use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\User\Role;
use Tagd\Core\Models\User\User;

trait NeedsResellers
{
    /**
     * Creates a reseller
     */
    protected function aReseller(): Reseller
    {
        return Reseller::factory()
            ->create();
    }

    /**
     * Acts as a reseller
     *
     * @return $this
     */
    protected function actingAsAReseller(Reseller $reseller = null, $guard = 'api'): static
    {
        if (is_null($reseller)) {
            $reseller = $this->aReseller();
        }

        $user = User::factory()
            ->firebase('resellers')
            ->create();
        $user->startActingAs($reseller);

        // tenant is injected by the auth provider, according to the bearer token
        $user->tenant = Role::RESELLER;

        return $this->actingAs($user, $guard);
    }
}
