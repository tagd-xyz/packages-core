<?php

namespace Tagd\Core\Tests\Unit\Models\Tagd;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tagd\Core\Tests\TestCase;
use Tagd\Core\Tests\Traits\NeedsTagds;

class TagdStateTest extends TestCase
{
    use NeedsTagds, RefreshDatabase;

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
