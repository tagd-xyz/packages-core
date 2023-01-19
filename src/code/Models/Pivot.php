<?php

namespace Tagd\Core\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Pivot extends Base
{
    /**
     * Constructor
     *
     * @param  array  $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnection(config('tagd.database.connection'));
    }
}
