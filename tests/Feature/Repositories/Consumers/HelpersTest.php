<?php

namespace Tagd\Core\Tests\Feature\Repositories\Consumers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tagd\Core\Repositories\Actors\Consumers;
use Tagd\Core\Tests\TestCase;
use Tagd\Core\Tests\Traits\NeedsConsumers;

class HelpersTest extends TestCase
{
    use NeedsConsumers, RefreshDatabase;

    public function testAssertExistsPrevious()
    {
        $repo = app(Consumers::class);

        $retailer = $this->aConsumer();

        $exists = $repo->assertExists($retailer->email, $retailer->name);
        $this->assertEquals($retailer->email, $exists->email);
        $this->assertEquals($retailer->name, $exists->name);
    }

    public function testAssertExistsPreviousWithName()
    {
        $repo = app(Consumers::class);

        $retailer = $this->aConsumer();
        $retailer->update([
            'name' => 'Somebody',
        ]);

        $exists = $repo->assertExists($retailer->email, 'New name');
        $this->assertEquals($retailer->email, $exists->email);
        $this->assertEquals($retailer->name, 'Somebody');
    }

    public function testAssertExistsNew()
    {
        $repo = app(Consumers::class);

        $exists = $repo->assertExists('somebody@gmail.com', 'Somebody');
        $this->assertEquals('somebody@gmail.com', $exists->email);
        $this->assertEquals('Somebody', $exists->name);
    }

    public function testFindByEmail()
    {
        $repo = app(Consumers::class);

        $retailer = $this->aConsumer();

        $exists = $repo->findByEmail($retailer->email);
        $this->assertEquals($retailer->id, $exists->id);
    }

    public function testNotFindByEmail()
    {
        $repo = app(Consumers::class);

        $this->withoutExceptionHandling();

        $this->assertThrows(
            function () use ($repo) {
                $repo->findByEmail('somebody@gmail.com');
            },
            ModelNotFoundException::class
        );
    }
}
