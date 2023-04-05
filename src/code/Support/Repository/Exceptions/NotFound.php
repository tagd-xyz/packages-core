<?php

namespace Tagd\Core\Support\Repository\Exceptions;

use Exception;

class NotFound extends Generic
{
    /**
     * Create a new exception instance.
     *
     * @return void
     */
    public function __construct(Exception $exception)
    {
        parent::__construct($exception->getMessage());
    }
}
