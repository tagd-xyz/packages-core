<?php

// phpcs:disable Symfony.Commenting.FunctionComment.Missing
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Models\Ref;

use Tagd\Core\Models\Model;

class Currency extends Model
{
    public const TABLE = 'currencies';

    protected $fillable = [
        'name',
        'code',
        'symbol',
    ];

    protected $casts = [
    ];

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();
    }
}
