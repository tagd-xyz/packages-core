<?php

namespace Tagd\Core\Repositories\Exceptions;

use Exception;

class NotFound extends Generic
{
    /**
     * Create a new exception instance.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function __construct(Exception $exception)
    {
        parent::__construct($exception->getMessage());
    }
}
