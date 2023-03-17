<?php

// phpcs:disable Symfony.Commenting.FunctionComment.Missing
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Models\Item;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Model;
use Tagd\Core\Models\Traits\HasUuidKey;

// use Tagd\Core\Events\Consumer\Created as ConsumerCreated;

class Item extends Model
{
    use
        HasFactory,
        HasUuidKey,
        SoftDeletes;

    protected $table = 'items';

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

        // static::created(function (Model $model) {
        //     ConsumerCreated::dispatch($model);
        // });
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

    public function tagds()
    {
        return $this->hasMany(Tagd::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    protected function rootTagd(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->tagds()->whereNull('prev_id')->first();
            }
        );
    }

    protected function currentTagd(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->tagds()->whereNull('next_id')->first();
            }
        );
    }

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
