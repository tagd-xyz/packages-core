<?php

// phpcs:disable Symfony.Commenting.FunctionComment.Missing
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Models\Actor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tagd\Core\Models\Item\Item;
use Tagd\Core\Models\Traits\HasUuidKey;

class Retailer extends Actor
{
    use
        HasFactory,
        HasUuidKey,
        SoftDeletes;

    protected $table = 'retailers';

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

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
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
