<?php

namespace Tagd\Core\TestsUnit\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tagd\Core\Tests\TestCase;
use Tagd\Core\Tests\Traits\NeedsTagds;

class TagdTreeTest extends TestCase
{
    use RefreshDatabase, NeedsTagds;

    public function testRoot()
    {
        $tagd = $this->aTagd();
        $this->assertEquals($tagd->isRoot, true);
    }

    public function testRootAndChild()
    {
        $tagd = $this->aTagd();
        $this->assertEquals($tagd->isRoot, true);

        $child = $this->aTagdChildOf($tagd);
        $this->assertEquals($child->isRoot, false);

        $this->assertEquals($child->parent->id, $tagd->id);
        $this->assertEquals($tagd->childrenCount, 1);
        $this->assertEquals($tagd->countAllChildren(), 1);
        $this->assertEquals($child->countAllAncestors(), 1);

        $this->assertEquals($tagd->buildChildrenCollection()->count(), 2);
    }

    public function testRootAndChildAndGrandchild()
    {
        $tagd = $this->aTagd();
        $this->assertEquals($tagd->isRoot, true);

        $child = $this->aTagdChildOf($tagd);
        $this->assertEquals($child->isRoot, false);

        $grandchild = $this->aTagdChildOf($child);
        $this->assertEquals($grandchild->isRoot, false);

        $this->assertEquals($child->parent->id, $tagd->id);
        $this->assertEquals($tagd->childrenCount, 1);
        $this->assertEquals($tagd->countAllChildren(), 2);
        $this->assertEquals($child->countAllAncestors(), 1);

        $this->assertEquals($grandchild->parent->id, $child->id);
        $this->assertEquals($child->childrenCount, 1);
        $this->assertEquals($child->countAllChildren(), 1);
        $this->assertEquals($grandchild->countAllAncestors(), 2);

        $this->assertEquals($tagd->buildChildrenCollection()->count(), 3);
    }
}
