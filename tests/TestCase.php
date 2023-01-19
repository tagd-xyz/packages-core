<?php

namespace Tagd\Core\Tests;

use Orchestra\Testbench\TestCase as Base;
use Tagd\Core\Database\Seeders\TestingSeeder;
use Tagd\Core\Database\Seeders\Traits\UsesFactories;
use Tagd\Core\Providers\Service;

class TestCase extends Base
{
    use UsesFactories;

    protected $seed = true;

    protected $seeder = TestingSeeder::class;

    public function setUp(): void
    {
        parent::setUp();

        // additional setup
        $this->setupFactories();
    }

    protected function getPackageProviders($app)
    {
        return [
            Service::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
