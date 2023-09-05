<?php

// phpcs:disable Symfony.Commenting.FunctionComment.Missing
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Models\Item;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Model;
use Tagd\Core\Models\Traits\HasTrustScore;
use Tagd\Core\Models\Traits\HasUuidKey;
use Tagd\Core\Support\Slug;

class Tagd extends Model
{
    use HasFactory,
        HasTrustScore,
        HasUuidKey,
        SoftDeletes;

    protected $table = 'tagds';

    protected $fillable = [
        'item_id',
        'consumer_id',
        'reseller_id',
        'meta',
        'stats',
        'parent_id',
        'status',
        'status_at',
        'trust',
    ];

    protected $casts = [
        'meta' => 'array',
        'stats' => 'array',
        'status_at' => 'datetime',
        'status' => TagdStatus::class,
        'trust' => 'array',
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
            if (! $model->status) {
                $model->status = TagdStatus::INACTIVE;
            }
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

    public function allChildren()
    {
        return $this->children()->with('children');
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
            get: fn () => TagdStatus::ACTIVE == $this->status,
        );
    }

    protected function isExpired(): Attribute
    {
        return Attribute::make(
            get: fn () => TagdStatus::EXPIRED == $this->status,
        );
    }

    protected function isCancelled(): Attribute
    {
        return Attribute::make(
            get: fn () => TagdStatus::CANCELLED == $this->status,
        );
    }

    protected function isResale(): Attribute
    {
        return Attribute::make(
            get: fn () => TagdStatus::RESALE == $this->status,
        );
    }

    protected function isTransferred(): Attribute
    {
        return Attribute::make(
            get: fn () => TagdStatus::TRANSFERRED == $this->status,
        );
    }

    protected function isReturned(): Attribute
    {
        return Attribute::make(
            get: fn () => TagdStatus::RETURNED == $this->status,
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

    protected function childrenCount(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->children()->count();
            }
        );
    }

    protected function root(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->is_root
                    ? $this
                    : $this->parent->root;
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Scope a query to only include root tagds.
     */
    public function scopeRoots(Builder $query): void
    {
        $query->whereIsNull('parent_id');
    }

    /**
     * Scope a query to only include leaf tagds.
     */
    public function scopeLeafs(Builder $query): void
    {
        $query->whereDoesntHave('children');
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIONS
    |--------------------------------------------------------------------------
    */

    public function activate(Carbon $date = null)
    {
        $updated = $this->update([
            'status' => TagdStatus::ACTIVE,
            'status_at' => $date
                ? $date
                : Carbon::now(),
        ]);
    }

    public function deactivate(Carbon $date = null)
    {
        $updated = $this->update([
            'status' => TagdStatus::INACTIVE,
            'status_at' => $date
                ? $date
                : Carbon::now(),
        ]);
    }

    public function expire(Carbon $date = null)
    {
        $updated = $this->update([
            'status' => TagdStatus::EXPIRED,
            'status_at' => $date
                ? $date
                : Carbon::now(),
        ]);
    }

    public function transfer(Carbon $date = null)
    {
        $updated = $this->update([
            'status' => TagdStatus::TRANSFERRED,
            'status_at' => $date
                ? $date
                : Carbon::now(),
        ]);
    }

    public function cancel(Carbon $date = null)
    {
        $updated = $this->update([
            'status' => TagdStatus::CANCELLED,
            'status_at' => $date
                ? $date
                : Carbon::now(),
        ]);
    }

    public function return(Carbon $date = null)
    {
        $updated = $this->update([
            'status' => TagdStatus::RETURNED,
            'status_at' => $date
                ? $date
                : Carbon::now(),
        ]);
    }

    public function enableForResale(bool $enabled = true)
    {
        $this->update([
            'meta' => [
                ...(array) $this->meta,
                (TagdMeta::AVAILABLE_FOR_RESALE)->value => $enabled,
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function buildChildrenCollection(callable $filter = null): Collection
    {
        $collection = collect();

        if (is_null($filter) || $filter($this)) {
            $collection->push($this);
        }

        foreach ($this->children as $child) {
            $collection = $collection->concat(
                $child->buildChildrenCollection($filter)
            );
        }

        return $collection;
    }

    public function buildParentCollection(callable $filter = null): Collection
    {
        $collection = collect();

        $parent = $this->parent;
        if ($parent) {
            if (is_null($filter) || $filter($parent)) {
                $collection->push($parent);
            }

            $collection = $collection->concat(
                $parent->buildParentCollection($filter)
            );
        }

        return $collection;
    }

    public function countAllAncestors(callable $filter = null): int
    {
        $count = 0;

        $parent = $this->parent;
        if ($parent) {
            if (is_null($filter) || $filter($parent)) {
                $count++;
            }
            $count += $parent->countAllAncestors($filter);
        }

        return $count;
    }

    public function countAllChildren(callable $filter = null): int
    {
        $count = 0;
        foreach ($this->children as $child) {
            if (is_null($filter) || $filter($child)) {
                $count++;
            }
            $count += $child->countAllChildren($filter);
        }

        return $count;
    }
}
