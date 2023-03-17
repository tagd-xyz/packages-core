<?php

// phpcs:ignoreFile

namespace Tagd\Core\Models\Item;

enum TagdMeta: string
{
    use \Tagd\Core\Support\Enum;

    case AVAILABLE_FOR_RESALE = 'available_for_resale';

    public function description(): string
    {
        return match ($this) {
            self::AVAILABLE_FOR_RESALE => 'Available for resale',
        };
    }
}
