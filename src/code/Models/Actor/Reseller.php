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
use Tagd\Core\Models\Upload\Upload;

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
        'avatar_upload_id',
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

    public function avatar_uploads()
    {
        return $this->hasMany(Upload::class, 'id', 'avatar_upload_id');
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
        return $this->avatar_uploads()->orderBy('created_at', 'desc')->limit(1)->first();
    }

    /**
     * get avatar_url_small attribute
     *
     * @return string
     */
    public function getAvatarSmallUrlAttribute(): ?string
    {
        $avatar = $this->avatar;

        if ($avatar) {
            return $this->getTransformedUploadUrl(
                $avatar->full_path,
                function ($sih) {
                    return $sih->square(100)->focusOnFace();
                }
            );
        } else {
            return null;
        }
    }

    /**
     * get avatar_url attribute
     *
     * @return string
     */
    public function getAvatarUrlAttribute(): ?string
    {
        $avatar = $this->avatar;

        if ($avatar) {
            return $this->getTransformedUploadUrl(
                $avatar->full_path,
                function ($sih) {
                    return $sih->square(640)->focusOnFace();
                }
            );
        } else {
            return null;
        }
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
