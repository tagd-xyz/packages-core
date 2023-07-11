<?php

//phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Tests\Feature\Command\Ref\TrustSettings;

use Tagd\Core\Tests\Command\Base;

class BrandTest extends Base
{
    /**
     * tagd:ref:trust-settings:brand-modifier
     *
     * @return void
     */
    public function test_cmd_ref_trust_settings_brand()
    {
        $this->artisan('tagd:ref:trust-settings:brand-modifier ' . 'gucci')
            ->assertSuccessful()
            ->expectsOutputToContain('gucci | 50');
    }

    /**
     * tagd:ref:trust-settings:brand-modifier --set
     *
     * @return void
     */
    public function test_cmd_ref_trust_settings_set_brand()
    {
        $this->artisan('tagd:ref:trust-settings:brand-modifier ' . 'gucci' . ' --set=100')
            ->assertSuccessful()
            ->expectsOutputToContain('gucci | 100');
    }
}
