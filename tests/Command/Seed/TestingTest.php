<?php

//phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Tests\Command\Seed;

use Tagd\Core\Tests\Command\Base;

class TestingTest extends Base
{
    /**
     * tagd:seed:test
     *
     * @return void
     */
    public function test_cmd_seed_test()
    {
        $this->artisan('tagd:seed:test')
            ->assertSuccessful();
    }
}
