<?php

//phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Tests\Feature\Command\Ref\TrustSettings;

use Tagd\Core\Tests\Command\Base;

class QaTest extends Base
{
    /**
     * tagd:seed:qa
     *
     * @return void
     */
    public function test_cmd_seed_qa()
    {
        $this->artisan('tagd:seed:qa')
            ->assertSuccessful();
    }
}
