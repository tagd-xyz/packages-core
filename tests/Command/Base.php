<?php

namespace Tagd\Core\Tests\Command;

use Tagd\Core\Database\Seeders\Traits\UsesFactories;
use Tagd\Core\Tests\TestCase;
use Tagd\Core\Tests\Traits\NeedsDatabase;
use Tagd\Core\Tests\Traits\NeedsRetailers;

abstract class Base extends TestCase
{
    use NeedsDatabase, NeedsRetailers, UsesFactories;

    /**
     * setUp any test
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setupFactories();
    }
}
