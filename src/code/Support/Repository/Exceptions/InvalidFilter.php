<?php

namespace Tagd\Core\Support\Repository\Exceptions;

use Illuminate\Validation\ValidationException;

class InvalidFilter extends ValidationException
{
    /**
     * Invalid filter custom exception
     *
     * @param  mixed  $field
     * @return static
     */
    public static function forField($field)
    {
        return self::withMessages([
            $field => 'Not allowed to filter by ' . $field,
        ]);
    }
}
