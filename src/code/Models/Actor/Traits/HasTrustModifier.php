<?php

namespace Tagd\Core\Models\Actor\Traits;

use Tagd\Core\Models\Ref\TrustSetting;

trait HasTrustModifier
{
    /*
    |--------------------------------------------------------------------------
    | ACCESSORS & MUTATORS
    |--------------------------------------------------------------------------
    */

    public function getTrustModifierAttribute()
    {
        return $this->trust['modifier'] ?? TrustSetting::MODIFIER_DEFAULT;
    }

    public function setTrustModifierAttribute($value)
    {
        throw_if(
            $value < TrustSetting::MODIFIER_MIN || $value > TrustSetting::MODIFIER_MAX,
            new \InvalidArgumentException(
                sprintf(
                    'Trust modifier must be between %d and %d',
                    TrustSetting::MODIFIER_MIN,
                    TrustSetting::MODIFIER_MAX
                )
            )
        );

        $this->trust = [
            ...$this->trust ?? [],
            'modifier' => $value,
        ];
    }
}
