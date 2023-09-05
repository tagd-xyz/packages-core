<?php

namespace Tagd\Core\Models\Traits;

use Tagd\Core\Models\Ref\TrustSetting;

trait HasTrustScore
{
    /*
    |--------------------------------------------------------------------------
    | ACCESSORS & MUTATORS
    |--------------------------------------------------------------------------
    */

    public function getTrustScoreAttribute()
    {
        return $this->trust['score'] ?? TrustSetting::SCORE_DEFAULT;
    }

    public function getTrustScoreSimpleAttribute(): int
    {
        $step = (TrustSetting::SCORE_MAX - TrustSetting::SCORE_MIN) / 5;
        for ($i = $step; $i <= TrustSetting::SCORE_MAX; $i += $step) {
            if ($this->trust_score <= $i) {
                return intval($i / $step);
            }
        }
    }

    public function setTrustScoreAttribute($value)
    {
        throw_if(
            $value < TrustSetting::SCORE_MIN || $value > TrustSetting::SCORE_MAX,
            new \InvalidArgumentException(
                sprintf(
                    'Trust score must be between %d and %d',
                    TrustSetting::SCORE_MIN,
                    TrustSetting::SCORE_MAX
                )
            )
        );

        $this->trust = [
            ...(array) $this->trust ?? [],
            'score' => $value,
        ];
    }
}
