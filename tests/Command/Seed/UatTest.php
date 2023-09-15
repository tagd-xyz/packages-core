<?php

//phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Tests\Command\Seed;

use Tagd\Core\Tests\Command\Base;

class UatTest extends Base
{
    /**
     * tagd:seed:uat
     *
     * @return void
     */
    public function test_cmd_seed_uat()
    {
        $this->artisan('tagd:seed:uat')
            ->assertSuccessful();
    }
}
