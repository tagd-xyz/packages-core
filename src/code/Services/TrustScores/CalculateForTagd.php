<?php

namespace Tagd\Core\Services\TrustScores;

use Illuminate\Support\Facades\DB;
use Tagd\Core\Models\Item\Tagd;
use Tagd\Core\Models\Ref\TrustSetting;
use Tagd\Core\Services\TrustScores\Rules\ConsumerReputation as ConsumerReputationRule;
use Tagd\Core\Services\TrustScores\Rules\Inheritance as InheritanceRule;
use Tagd\Core\Services\TrustScores\Rules\ItemBrand as ItemBrandRule;
use Tagd\Core\Services\TrustScores\Rules\NearestNeighbour as NearestNeighbourRule;
use Tagd\Core\Services\TrustScores\Rules\TimeElapsed as TimeElapsedRule;

trait CalculateForTagd
{
    public function rules(): array
    {
        return [
            InheritanceRule::class,
            TimeElapsedRule::class,
            ConsumerReputationRule::class,
            ItemBrandRule::class,
            NearestNeighbourRule::class,
        ];
    }

    /**
     * Calculate trust score for a tagd
     */
    public function calculateForTagd(
        Tagd $tagd
    ): Tagd {

        return DB::transaction(function () use (
            $tagd
        ) {
            // start with the minimum score
            $score = TrustSetting::SCORE_MIN;

            // apply all rules
            foreach ($this->rules() as $class) {
                $rule = new $class($tagd);
                $score += $rule->apply();
            }

            // save the score (truncate to max)
            $tagd->trust_score = min($score, TrustSetting::SCORE_MAX);
            $tagd->save();

            return $tagd;
        });
    }
}
