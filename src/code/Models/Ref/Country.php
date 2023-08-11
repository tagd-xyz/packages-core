<?php

// phpcs:disable Symfony.Commenting.FunctionComment.Missing
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Models\Ref;

use Tagd\Core\Models\Model;

class Country extends Model
{
    public const TABLE = 'counries';

    protected $fillable = [
        'name',
        'code',
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
