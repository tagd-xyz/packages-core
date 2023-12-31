<?php

namespace Tagd\Core\Models;

use Illuminate\Database\Eloquent\Model as Base;

class Model extends Base
{
    /**
     * Constructor
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnection(config('tagd.database.connection'));
    }
}
