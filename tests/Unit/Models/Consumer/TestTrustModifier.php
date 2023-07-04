<?php

namespace Tagd\Core\Tests\Unit\Models\Consumer;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tagd\Core\Models\Ref\TrustSetting;
use Tagd\Core\Tests\TestCase;
use Tagd\Core\Tests\Traits\NeedsConsumers;

class TestTrustModifier extends TestCase
{
    use RefreshDatabase, NeedsConsumers;

    public function testGetTrustModifierDefault()
    {
        $consumer = $this->aConsumer();

        $this->assertEquals(TrustSetting::MODIFIER_DEFAULT, $consumer->trust_modifier);
    }

    public function testSetTrustModifier()
    {
        $consumer = $this->aConsumer();

        $consumer->trust_modifier = 20;

        $this->assertEquals(20, $consumer->trust_modifier);
    }

    public function testSetTrustModifierOutOfRange()
    {
        $consumer = $this->aConsumer();

        $this->assertThrows(
            function () use ($consumer) {
                $consumer->trust_modifier = TrustSetting::MODIFIER_MIN - 1;
            },
            \InvalidArgumentException::class
        );

        $this->assertThrows(
            function () use ($consumer) {
                $consumer->trust_modifier = TrustSetting::MODIFIER_MAX + 1;
            },
            \InvalidArgumentException::class
        );
    }
}
