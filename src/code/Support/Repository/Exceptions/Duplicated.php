<?php

namespace Tagd\Core\Support\Repository\Exceptions;

class Duplicated extends Generic
{
    /**
     * Create a new exception instance.
     *
     * @return void
     */
    public function __construct(string $message = 'Duplicated entry')
    {
        parent::__construct($message);
    }
}
