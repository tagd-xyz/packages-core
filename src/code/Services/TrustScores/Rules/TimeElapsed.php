<?php

namespace Tagd\Core\Services\TrustScores\Rules;

use Tagd\Core\Models\Item\TagdStatus;
use Tagd\Core\Models\Ref\TrustSetting;

class TimeElapsed extends Base
{
    /**
     * Apply the rule
     *
     * @throws BindingResolutionException
     */
    public function apply(): float
    {
        // apply only on resale
        if ($this->tagd->status != TagdStatus::RESALE) {
            return TrustSetting::SCORE_DEFAULT;
        }

        // calculate time elapsed between resale and sale
        $listedAt = $this->tagd->created_at;
        $soldAt = $this->tagd->parent->created_at;
        $diff = $listedAt->diffInHours($soldAt);

        // calculate score based on time elapsed
        if ($diff < 4) {
            return 20.0;
        } elseif ($diff < 24) {
            return 10.0;
        } elseif ($diff < 48) {
            return 20.0;
        } else {
            return 0.0;
        }
    }
}
