<?php

namespace Tagd\Core\Models\Exceptions;

class Generic extends \Exception
{
    /**
     * Create a new exception instance.
     *
     * @return void
     */
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
