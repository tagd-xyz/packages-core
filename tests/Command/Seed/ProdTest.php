<?php

//phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Tests\Command\Seed;

use Tagd\Core\Tests\Command\Base;

class ProdTest extends Base
{
    /**
     * tagd:seed:prod
     *
     * @return void
     */
    public function test_cmd_seed_prod()
    {
        $this->artisan('tagd:seed:prod')
            ->assertSuccessful();
    }
}
