<?php

// phpcs:disable Symfony.Commenting.FunctionComment.Missing
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Models\Item;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\Model;
use Tagd\Core\Models\Traits\HasUuidKey;
use Tagd\Core\Support\Slug;

class Tagd extends Model
{
    use
        HasFactory,
        HasUuidKey,
        SoftDeletes;

    protected $table = 'tagds';

    protected $fillable = [
        'item_id',
        'consumer_id',
        'meta',
        'activated_at',
    ];

    protected $casts = [
        'meta' => 'array',
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
        static::creating(function ($model) {
            $model->slug = (new Slug())->toString();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function consumer()
    {
        return $this->belongsTo(Consumer::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: fn () => ! is_null($this->activated_at),
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

    public function activate()
    {
        $updated = $this->update([
            'activated_at' => Carbon::now(),
        ]);
    }
}
