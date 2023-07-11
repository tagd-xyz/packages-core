<?php

//phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Tests\Command\Seed;

use Tagd\Core\Tests\Command\Base;

class DevTest extends Base
{
    /**
     * tagd:seed:dev
     *
     * @return void
     */
    public function test_cmd_seed_dev()
    {
        $this->artisan('tagd:seed:dev')
            ->assertSuccessful();
    }
}
