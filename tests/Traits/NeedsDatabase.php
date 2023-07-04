<?php

namespace Tagd\Core\Tests\Traits;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tagd\Core\Database\Seeders\TestingSeeder;

trait NeedsDatabase
{
    use RefreshDatabase;

    protected $seed = true;

    /**
     * Run a specific seeder before each test.
     *
     * @var string
     */
    protected $seeder = TestingSeeder::class;
}
