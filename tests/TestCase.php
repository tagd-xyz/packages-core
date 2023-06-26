<?php

namespace Tagd\Core\Tests;

use Illuminate\Support\Facades\Notification;
use Orchestra\Testbench\TestCase as Base;
use Tagd\Core\Database\Seeders\TestingSeeder;
use Tagd\Core\Database\Seeders\Traits\UsesFactories;
use Tagd\Core\Providers\TagdServiceProvider;

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

        Notification::fake();
    }

    protected function getPackageProviders($app)
    {
        return [
            TagdServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
