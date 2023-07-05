<?php

namespace Tagd\Core\Tests\Feature\Repositories\RefTrustSettings;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tagd\Core\Models\Ref\TrustSetting;
use Tagd\Core\Repositories\Ref\TrustSettings;
use Tagd\Core\Support\Repository\Exceptions\InvalidData as InvalidDataException;
use Tagd\Core\Tests\TestCase;

class BrandModifiersTest extends TestCase
{
    use RefreshDatabase;

    public function testSetModifierForBrand()
    {
        $repo = app(TrustSettings::class);

        $modifier = $repo->setModifierForBrand('brand', 20);

        $this->assertEquals($modifier, 20);
    }

    public function testGetModifierForBrand()
    {
        $repo = app(TrustSettings::class);

        $repo->setModifierForBrand('brand', 20);

        $modifier = $repo->getModifierForBrand('brand');

        $this->assertEquals($modifier, 20);
    }

    public function testGetModifierForBrandDefault()
    {
        $repo = app(TrustSettings::class);

        $modifier = $repo->getModifierForBrand('brand');

        $this->assertEquals($modifier, TrustSetting::MODIFIER_DEFAULT);
    }

    public function testSetModifierForBrandOutOfRange()
    {
        $repo = app(TrustSettings::class);

        $this->withoutExceptionHandling();

        $this->assertThrows(
            function () use ($repo) {
                $modifier = $repo->setModifierForBrand('brand', TrustSetting::MODIFIER_MIN - 1);
            },
            InvalidDataException::class
        );

        $this->assertThrows(
            function () use ($repo) {
                $modifier = $repo->setModifierForBrand('brand', TrustSetting::MODIFIER_MAX + 1);
            },
            InvalidDataException::class
        );
    }
}
