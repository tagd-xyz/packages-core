<?php

namespace Tagd\Core\Tests\Unit\Models\Tagd;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tagd\Core\Models\Ref\TrustSetting;
use Tagd\Core\Services\ResellerSales\Service as ResellerSalesService;
use Tagd\Core\Tests\TestCase;
use Tagd\Core\Tests\Traits\NeedsResellers;
use Tagd\Core\Tests\Traits\NeedsTagds;

class TagdTrustScoreTest extends TestCase
{
    use NeedsResellers, NeedsTagds, RefreshDatabase;

    public function testGetTrustScoreDefault()
    {
        $tagd = $this->aTagd();
        $this->assertEquals(TrustSetting::SCORE_DEFAULT, $tagd->trust_score);
    }

    public function testSetTrustScore()
    {
        $tagd = $this->aTagd();
        $tagd->trust_score = 50;
        $this->assertEquals(50, $tagd->trust_score);
    }

    public function testGetTrustScoreSimple()
    {
        $tagd = $this->aTagd();

        $tagd->trust_score = 10;
        $this->assertEquals(1, $tagd->trust_score_simple);

        $tagd->trust_score = 21;
        $this->assertEquals(2, $tagd->trust_score_simple);

        $tagd->trust_score = 50;
        $this->assertEquals(3, $tagd->trust_score_simple);

        $tagd->trust_score = 70;
        $this->assertEquals(4, $tagd->trust_score_simple);

        $tagd->trust_score = 100;
        $this->assertEquals(5, $tagd->trust_score_simple);
    }

    public function testSetTrustScoreOutOfRange()
    {
        $tagd = $this->aTagd();

        $this->assertThrows(
            function () use ($tagd) {
                $tagd->trust_score = TrustSetting::SCORE_MIN - 1;
            },
            \InvalidArgumentException::class
        );

        $this->assertThrows(
            function () use ($tagd) {
                $tagd->trust_score = TrustSetting::SCORE_MAX + 1;
            },
            \InvalidArgumentException::class
        );
    }

    public function testTrustScoreInheritance()
    {
        $repo = app(ResellerSalesService::class);

        $parentTagd = $this->aTagd();
        $parentTagd->trust_score = 50;

        $reseller = $this->aReseller();

        $tagd = $repo->startResellerSale($reseller, $parentTagd);

        $this->assertEquals($parentTagd->trust_score, $tagd->trust_score);
    }
}
