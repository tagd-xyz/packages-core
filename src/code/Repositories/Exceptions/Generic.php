<?php

namespace Tagd\Core\Repositories\Exceptions;

class Generic extends \Exception
{
    /**
     * Create a new exception instance.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
