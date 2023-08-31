<?php

namespace Tagd\Core\Tests\Feature\Repositories\Resellers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tagd\Core\Repositories\Actors\Resellers;
use Tagd\Core\Tests\TestCase;
use Tagd\Core\Tests\Traits\NeedsResellers;

class HelpersTest extends TestCase
{
    use NeedsResellers, RefreshDatabase;

    public function testAssertExistsPrevious()
    {
        $repo = app(Resellers::class);

        $retailer = $this->aReseller();

        $exists = $repo->assertExists($retailer->email, $retailer->name);
        $this->assertEquals($retailer->email, $exists->email);
        $this->assertEquals($retailer->name, $exists->name);
    }

    public function testAssertExistsPreviousWithName()
    {
        $repo = app(Resellers::class);

        $retailer = $this->aReseller();
        $retailer->update([
            'name' => 'Somebody',
        ]);

        $exists = $repo->assertExists($retailer->email, 'New name');
        $this->assertEquals($retailer->email, $exists->email);
        $this->assertEquals($retailer->name, 'Somebody');
    }

    public function testAssertExistsNew()
    {
        $repo = app(Resellers::class);

        $exists = $repo->assertExists('somebody@gmail.com', 'Somebody');
        $this->assertEquals('somebody@gmail.com', $exists->email);
        $this->assertEquals('Somebody', $exists->name);
    }
}
