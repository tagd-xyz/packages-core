<?php

namespace Tagd\Core\Services\TrustScores;

use Illuminate\Support\Facades\DB;
use Tagd\Core\Models\Item\Tagd;
use Tagd\Core\Services\TrustScores\Rules\ConsumerReputation as ConsumerReputationRule;
use Tagd\Core\Services\TrustScores\Rules\ItemBrand as ItemBrandRule;
use Tagd\Core\Services\TrustScores\Rules\TimeElapsed as TimeElapsedRule;

trait CalculateForTagd
{
    public function rules(): array
    {
        return [
            TimeElapsedRule::class,
            ConsumerReputationRule::class,
            ItemBrandRule::class,
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
            $score = $tagd->trust_score;

            foreach ($this->rules() as $class) {
                $rule = new $class($tagd);
                $score += $rule->apply();
            }

            $tagd->trust_score = $score;
            $tagd->save();

            return $tagd;
        });
    }
}
