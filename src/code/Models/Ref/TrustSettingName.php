<?php

// phpcs:ignoreFile

namespace Tagd\Core\Models\Ref;

enum TrustSettingName: string
{
    use \Tagd\Core\Support\Enum;

    case BRAND_MODIFIER = 'brand_modifier';

    public function description(): string
    {
        return match ($this) {
            self::BRAND_MODIFIER => 'Brand modifier',
        };
    }
}
