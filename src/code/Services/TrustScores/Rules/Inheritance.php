<?php

namespace Tagd\Core\Services\TrustScores\Rules;

use Illuminate\Contracts\Container\BindingResolutionException;
use Tagd\Core\Models\Ref\TrustSetting;

class Inheritance extends Base
{
    /**
     * Apply the rule
     *
     * @throws BindingResolutionException
     */
    public function apply(): float
    {
        // inherits score from parent
        if ($this->tagd->is_root) {
            return TrustSetting::SCORE_DEFAULT;
        } else {
            return $this->tagd->parent->trust_score ?? TrustSetting::SCORE_DEFAULT;
        }
    }
}
