<?php

// phpcs:ignoreFile

namespace Tagd\Core\Models\Item;

enum TagdStatus: string
{
    use \Tagd\Core\Support\Enum;

    case TRANSFERRED = 'transferred';
    case CANCELLED = 'cancelled';
    case EXPIRED = 'expired';
    case INACTIVE = 'inactive';
    case ACTIVE = 'active';

    public function description(): string
    {
        return match ($this) {
            self::TRANSFERRED => 'Transferred',
            self::CANCELLED => 'Cancelled',
            self::EXPIRED => 'Expired',
            self::INACTIVE => 'Inactive',
            self::ACTIVE => 'Active',
        };
    }
}
