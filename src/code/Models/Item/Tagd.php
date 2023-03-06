<?php

// phpcs:disable Symfony.Commenting.FunctionComment.Missing
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Models\Item;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\Actor\Reseller;
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
        'reseller_id',
        'meta',
        'parent_id',
        'activated_at',
        'expired_at',
        'transferred_at',
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

    public function reseller()
    {
        return $this->belongsTo(Reseller::class);
    }

    public function children()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
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

    protected function isExpired(): Attribute
    {
        return Attribute::make(
            get: fn () => ! is_null($this->expired_at),
        );
    }

    protected function isTransferred(): Attribute
    {
        return Attribute::make(
            get: fn () => ! is_null($this->transferred_at),
        );
    }

    protected function isRoot(): Attribute
    {
        return Attribute::make(
            get: fn () => is_null($this->parent_id),
        );
    }

    protected function isAvailableForResale(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->meta['is_available_for_resale'] ?? false,
        );
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->isTransferred) {
                    return TagdStatus::TRANSFERRED;
                } elseif ($this->isExpired) {
                    return TagdStatus::EXPIRED;
                } elseif ($this->isActive) {
                    return TagdStatus::ACTIVE;
                } else {
                    return TagdStatus::INACTIVE;
                }
            },
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

    public function expire()
    {
        $updated = $this->update([
            'expired_at' => Carbon::now(),
        ]);
    }

    public function transfer()
    {
        $updated = $this->update([
            'transferred_at' => Carbon::now(),
        ]);
    }

    public function enableForResale(bool $enabled = true)
    {
        $this->update([
            'meta' => [
                ...$this->meta,
                'is_available_for_resale' => $enabled,
            ],
        ]);
    }
}
