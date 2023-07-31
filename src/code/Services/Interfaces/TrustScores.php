<?php

namespace Tagd\Core\Services\Interfaces;

use Tagd\Core\Models\Item\Tagd as Tagd;

interface TrustScores
{
    /**
     * Calculate trust score for a tagd
     */
    public function calculateForTagd(
        Tagd $tagd
    ): Tagd;
}
