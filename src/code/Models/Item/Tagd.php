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
    use HasFactory,
        HasUuidKey,
        SoftDeletes;

    protected $table = 'tagds';

    protected $fillable = [
        'item_id',
        'consumer_id',
        'reseller_id',
        'meta',
        'parent_id',
        'status',
        'status_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'status_at' => 'datetime',
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

    public function auctions()
    {
        return $this->hasMany(static::class, 'parent_id')->where('status', TagdStatus::RESALE);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: fn () => (TagdStatus::ACTIVE)->value == $this->status,
        );
    }

    protected function isExpired(): Attribute
    {
        return Attribute::make(
            get: fn () => (TagdStatus::EXPIRED)->value == $this->status,
        );
    }

    protected function isCancelled(): Attribute
    {
        return Attribute::make(
            get: fn () => (TagdStatus::CANCELLED)->value == $this->status,
        );
    }

    protected function isResale(): Attribute
    {
        return Attribute::make(
            get: fn () => (TagdStatus::RESALE)->value == $this->status,
        );
    }

    protected function isTransferred(): Attribute
    {
        return Attribute::make(
            get: fn () => (TagdStatus::TRANSFERRED)->value == $this->status,
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
            get: fn () => $this->meta[
                (TagdMeta::AVAILABLE_FOR_RESALE)->value
            ] ?? false,
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
            'status' => TagdStatus::ACTIVE,
            'status_at' => Carbon::now(),
        ]);
    }

    public function expire()
    {
        $updated = $this->update([
            'status' => TagdStatus::EXPIRED,
            'status_at' => Carbon::now(),
        ]);
    }

    public function transfer()
    {
        $updated = $this->update([
            'status' => TagdStatus::TRANSFERRED,
            'status_at' => Carbon::now(),
        ]);
    }

    public function cancel()
    {
        $updated = $this->update([
            'status' => TagdStatus::CANCELLED,
            'status_at' => Carbon::now(),
        ]);
    }

    public function enableForResale(bool $enabled = true)
    {
        $this->update([
            'meta' => [
                ...$this->meta,
                (TagdMeta::AVAILABLE_FOR_RESALE)->value => $enabled,
            ],
        ]);
    }
}
