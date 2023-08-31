<?php

// phpcs:disable Symfony.Commenting.FunctionComment.Missing
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Models\Actor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tagd\Core\Models\Traits\HasUuidKey;

class Admin extends Actor
{
    use HasFactory,
        HasUuidKey,
        SoftDeletes;

    protected $table = 'admins';

    protected $fillable = [
        'name',
        'email',
    ];

    protected $casts = [
    ];

    protected $observables = [
    ];

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();
        static::autoUuidKey();
    }
}
