<?php

// phpcs:disable Symfony.Commenting.FunctionComment.Missing
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Models\Item;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Model;
use Tagd\Core\Models\Traits\HasUuidKey;

class Stock extends Model
{
    use
        HasFactory,
        HasUuidKey,
        SoftDeletes;

    protected $table = 'stock';

    protected $fillable = [
        'retailer_id',
        'type',
        'name',
        'description',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    protected $observables = [
        // 'created',
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

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }

    public function propertiesBags()
    {
        return $this->hasMany(PropertyBags::class);
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
