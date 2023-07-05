<?php

namespace Tagd\Core\Repositories\Interfaces\Ref;

use Tagd\Core\Support\Repository\Interfaces\Repository;

interface TrustSettings extends Repository
{
    /**
     * Set the modifier for a given brand.
     */
    public function setModifierForBrand(string $brandName, float $value): float;

    /**
     * Return the modifier for a given brand.
     */
    public function getModifierForBrand(string $brandName): float;

    /**
     * Return the modifiers for all brands.
     */
    public function getModifierForBrands(): array;
}
