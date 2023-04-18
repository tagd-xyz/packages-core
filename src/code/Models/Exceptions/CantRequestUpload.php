<?php

namespace Tagd\Core\Models\Exceptions;

class CantRequestUpload extends Generic
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
