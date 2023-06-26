<?php

namespace Tagd\Core\TestsUnit\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tagd\Core\Tests\TestCase;
use Tagd\Core\Tests\Traits\NeedsTagds;

class TagdStateTest extends TestCase
{
    use RefreshDatabase, NeedsTagds;

    public function testDefaultMeta()
    {
        $tagd = $this->aTagd();

        $this->assertEquals($tagd->isAvailableForResale, false);
    }

    public function testAvailableForResaleMeta()
    {
        $tagd = $this->aTagd();
        $tagd->enableForResale();

        $this->assertEquals($tagd->isAvailableForResale, true);
    }
}
