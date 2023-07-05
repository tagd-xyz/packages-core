<?php

namespace Tagd\Core\Services\TrustScores\Rules;

use Illuminate\Contracts\Container\BindingResolutionException;
use Tagd\Core\Models\Item\TagdStatus;
use Tagd\Core\Models\Ref\TrustSetting;

class ConsumerReputation extends Base
{
    /**
     * Apply the rule
     *
     * @throws BindingResolutionException
     */
    public function apply(): float
    {
        // apply only on resale
        if (TagdStatus::RESALE != $this->tagd->status) {
            return TrustSetting::SCORE_DEFAULT;
        }

        // get consumer from tagd
        $consumer = $this->tagd->parent->consumer ?? null;

        if (is_null($consumer)) {
            return TrustSetting::SCORE_DEFAULT;
        } else {
            // calculate score based on consumer's trust score
            $score = $consumer->trust_score;
            if (TrustSetting::SCORE_MIN == $score) {
                return 0.0;
            } else {
                switch ($this->modifier2step($score, 4)) {
                    case 1:
                        return 0.0;
                    case 2:
                        return 5.0;
                    case 3:
                        return 10.0;
                    case 4:
                        return 20.0;
                }
            }
        }
    }
}
