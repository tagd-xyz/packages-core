<?php

namespace Tagd\Core\Services\TrustScores;

use Tagd\Core\Services\Interfaces\TrustScores as TrustScoresInterface;

class Service implements TrustScoresInterface
{
    use CalculateForTagd;
}
