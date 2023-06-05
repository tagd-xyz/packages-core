<?php

// phpcs:disable Symfony.Commenting.FunctionComment.Missing
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Models\Actor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tagd\Core\Models\Item\Tagd;
use Tagd\Core\Models\Resale\AccessRequest;
use Tagd\Core\Models\Traits\HasUuidKey;

class Consumer extends Actor
{
    use HasFactory,
        HasUuidKey,
        SoftDeletes;

    protected $table = 'consumers';

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

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function tagds()
    {
        return $this->hasMany(Tagd::class);
    }

    public function accessRequests()
    {
        return $this->hasMany(AccessRequest::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACTIONS
    |--------------------------------------------------------------------------
    */
}
