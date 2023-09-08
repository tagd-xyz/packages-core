<?php

// phpcs:disable Symfony.Commenting.FunctionComment.Missing
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Models\Actor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tagd\Core\Models\Item\Tagd;
use Tagd\Core\Models\Resale\AccessRequest;
use Tagd\Core\Models\Traits\HasUpload;
use Tagd\Core\Models\Traits\HasUuidKey;

class Reseller extends Actor
{
    use HasFactory,
        HasUpload,
        HasUuidKey,
        SoftDeletes;

    protected $table = 'resellers';

    protected $fillable = [
        'name',
        'email',
        'website',
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

    public function images()
    {
        return $this->hasMany(ResellerImage::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * get avatar attribute
     */
    public function getAvatarAttribute()
    {
        return $this->images()->orderBy('created_at', 'desc')->limit(1)->first();
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
