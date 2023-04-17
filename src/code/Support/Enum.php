<?php

namespace Tagd\Core\Support;

trait Enum
{
    /**
     * Returns an array with all enum's values
     */
    public static function values(): array
    {
        return array_map(
            function ($case) {
                return $case->value;
            },
            self::cases()
        );
    }

    /**
     * Returns an array with all enum's names
     */
    public static function names(): array
    {
        return array_map(
            function ($case) {
                return $case->name;
            },
            self::cases()
        );
    }

    public static function fromName(string $name): self
    {
        foreach (self::cases() as $status) {
            if ($name === $status->name) {
                return $status;
            }
        }
        throw new \ValueError("$name is not a valid backing value for enum " . self::class);
    }

    /**
     * Check if enum instance is any of given items
     */
    public function isAnyOf(array $items): bool
    {
        return in_array($this, $items, true);
    }

    /**
     * Check if enum instance value is any of given values
     */
    public function isValueAnyOf(array $values): bool
    {
        return in_array($this->value, $values, true);
    }
}
