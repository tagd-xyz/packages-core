<?php

namespace Tagd\Core\Repositories\Exceptions;

use Exception;

class Duplicated extends Generic
{
    /**
     * Create a new exception instance.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct(string $message = 'Duplicated entry')
    {
        parent::__construct($message);
    }
}
