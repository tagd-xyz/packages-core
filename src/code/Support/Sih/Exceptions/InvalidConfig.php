<?php

namespace Tagd\Core\Support\Sih\Exceptions;

class InvalidConfig extends Generic
{
    /**
     * Create a new exception instance.
     *
     * @return void
     */
    public function __construct(string $message = 'Invalid parameters when creating SIH')
    {
        parent::__construct($message);
    }
}
