<?php

namespace Tagd\Core\Services\TrustScores\Rules;

use Tagd\Core\Models\Item\Tagd;
use Tagd\Core\Models\Ref\TrustSetting;

abstract class Base
{
    protected $tagd;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(Tagd $tagd)
    {
        $this->tagd = $tagd;
    }

    /**
     * Helper function to convert modifier to step
     *
     * @param  mixed  $modifier
     * @param  int  $chunks
     */
    protected function modifier2step($modifier, $chunks = 4): int
    {
        $step = (TrustSetting::SCORE_MAX - TrustSetting::SCORE_MIN) / $chunks;

        for ($i = $step; $i <= TrustSetting::SCORE_MAX; $i += $step) {
            if ($modifier <= $i) {
                return intval($i / $step);
            }
        }
    }

    /**
     * Apply the rule
     */
    abstract public function apply(): float;
}
