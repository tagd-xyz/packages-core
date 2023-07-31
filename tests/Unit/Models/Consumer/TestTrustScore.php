<?php

namespace Tagd\Core\Tests\Unit\Models\Consumer;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tagd\Core\Models\Ref\TrustSetting;
use Tagd\Core\Tests\TestCase;
use Tagd\Core\Tests\Traits\NeedsConsumers;

class TestTrustScore extends TestCase
{
    use RefreshDatabase, NeedsConsumers;

    public function testGetTrustScoreDefault()
    {
        $consumer = $this->aConsumer();

        $this->assertEquals(TrustSetting::SCORE_DEFAULT, $consumer->trust_score);
    }

    public function testSetTrustScore()
    {
        $consumer = $this->aConsumer();

        $consumer->trust_score = 20;

        $this->assertEquals(20, $consumer->trust_score);
    }

    public function testSetTrustScoreOutOfRange()
    {
        $consumer = $this->aConsumer();

        $this->assertThrows(
            function () use ($consumer) {
                $consumer->trust_score = TrustSetting::SCORE_MIN - 1;
            },
            \InvalidArgumentException::class
        );

        $this->assertThrows(
            function () use ($consumer) {
                $consumer->trust_score = TrustSetting::SCORE_MAX + 1;
            },
            \InvalidArgumentException::class
        );
    }
}
