<?php

// phpcs:disable Symfony.Commenting.FunctionComment.Missing
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Models\Item;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tagd\Core\Models\Model;
use Tagd\Core\Models\Traits\HasUpload;
use Tagd\Core\Models\Upload\Upload;

class ItemImage extends Model
{
    use HasFactory, HasUpload;

    public $table = 'items_images';

    protected $fillable = [
        'item_id',
        'upload_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function upload()
    {
        return $this->belongsTo(Upload::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * get small_url attribute
     *
     * @return string
     */
    public function getSmallUrlAttribute(): ?string
    {
        $upload = $this->upload;

        if ($upload) {
            return $this->getTransformedUploadUrl(
                $upload->full_path,
                function ($sih) {
                    return $sih->square(100)->fit('cover');
                }
            );
        } else {
            return null;
        }
    }

    /**
     * get url attribute
     *
     * @return string
     */
    public function getUrlAttribute(): ?string
    {
        $upload = $this->upload;

        if ($upload) {
            return $this->getTransformedUploadUrl(
                $upload->full_path,
                function ($sih) {
                    return $sih->width(1024)->height(768)->fit('contain');
                }
            );
        } else {
            return null;
        }
    }

    /**
     * get url attribute
     *
     * @return string
     */
    public function getPortraitUrlAttribute(): ?string
    {
        $upload = $this->upload;

        if ($upload) {
            return $this->getTransformedUploadUrl(
                $upload->full_path,
                function ($sih) {
                    return $sih->width(768)->height(1024)->fit('contain');
                }
            );
        } else {
            return null;
        }
    }

    /**
     * get url attribute
     *
     * @return string
     */
    public function getSquareUrlAttribute(): ?string
    {
        $upload = $this->upload;

        if ($upload) {
            return $this->getTransformedUploadUrl(
                $upload->full_path,
                function ($sih) {
                    return $sih->square(1024)->fit('inside');
                }
            );
        } else {
            return null;
        }
    }

    /**
     * get preview_url attribute
     *
     * @return string
     */
    public function getPreviewUrlAttribute(): ?string
    {
        $upload = $this->upload;

        if ($upload) {
            return $this->getTransformedUploadUrl(
                $upload->full_path,
                function ($sih) {
                    return $sih->square(640)->fit('cover');
                }
            );
        } else {
            return null;
        }
    }
}
