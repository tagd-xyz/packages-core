<?php

// phpcs:ignoreFile

namespace Tagd\Core\Models\Item;

enum TagdStatus: string
{
    use \Tagd\Core\Support\Enum;

    case INACTIVE = 'inactive';
    case ACTIVE = 'active';
    case RESALE = 'resale';
    case TRANSFERRED = 'transferred';
    case CANCELLED = 'cancelled';
    case EXPIRED = 'expired';
    case RETURNED = 'returned';

    public function description(): string
    {
        return match ($this) {
            self::INACTIVE => 'Inactive',
            self::ACTIVE => 'Active',
            self::RESALE => 'For Resale',
            self::TRANSFERRED => 'Transferred',
            self::CANCELLED => 'Cancelled',
            self::EXPIRED => 'Expired',
            self::RETURNED => 'Returned',
        };
    }
}
