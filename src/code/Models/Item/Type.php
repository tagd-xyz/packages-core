<?php

// phpcs:ignoreFile

namespace Tagd\Core\Models\Item;

enum Type: string
{
    use \Tagd\Core\Support\Enum;

    case FASHION = 'fashion';
    case SNEAKERS = 'sneakers';

    public function description(): string
    {
        return match ($this) {
            self::FASHION => 'Fashion',
            self::SNEAKERS => 'Sneakers',
        };
    }
}
