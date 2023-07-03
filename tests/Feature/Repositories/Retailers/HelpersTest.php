<?php

namespace Tagd\Core\Tests\Feature\Repositories\Retailers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tagd\Core\Repositories\Actors\Retailers;
use Tagd\Core\Tests\TestCase;
use Tagd\Core\Tests\Traits\NeedsRetailers;

class HelpersTest extends TestCase
{
    use RefreshDatabase, NeedsRetailers;

    public function testAssertExistsPrevious()
    {
        $repo = app(Retailers::class);

        $retailer = $this->aRetailer();

        $exists = $repo->assertExists($retailer->email, $retailer->name);
        $this->assertEquals($retailer->email, $exists->email);
        $this->assertEquals($retailer->name, $exists->name);
    }

    public function testAssertExistsPreviousWithName()
    {
        $repo = app(Retailers::class);

        $retailer = $this->aRetailer();
        $retailer->update([
            'name' => 'Somebody',
        ]);

        $exists = $repo->assertExists($retailer->email, 'New name');
        $this->assertEquals($retailer->email, $exists->email);
        $this->assertEquals($retailer->name, 'Somebody');
    }

    public function testAssertExistsNew()
    {
        $repo = app(Retailers::class);

        $exists = $repo->assertExists('somebody@gmail.com', 'Somebody');
        $this->assertEquals('somebody@gmail.com', $exists->email);
        $this->assertEquals('Somebody', $exists->name);
    }
}
