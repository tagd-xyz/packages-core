<?php

namespace Tagd\Core\Services\TrustScores\Rules;

use Illuminate\Contracts\Container\BindingResolutionException;

class ConsumerReputation extends Base
{
    /**
     * Apply the rule
     *
     * @throws BindingResolutionException
     */
    public function apply(): float
    {
        // get consumer from tagd
        $consumer = $this->tagd->parent->consumer ?? null;

        if (is_null($consumer)) {
            return 0.0;
        } else {
            // calculate score based on consumer's trust score
            $score = $consumer->trust_score;
            if (0.0 == $score) {
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
