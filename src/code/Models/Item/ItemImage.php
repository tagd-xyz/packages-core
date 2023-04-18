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
     * get image_url_small attribute
     *
     * @return string
     */
    public function getImageSmallUrlAttribute(): ?string
    {
        return $this->getTransformedUploadUrl(
            'upload',
            function ($sih) {
                return $sih->square(250);
            }
        );
    }

    /**
     * get image_url attribute
     *
     * @return string
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->getTransformedUploadUrl(
            'upload',
            function ($sih) {
                return $sih->square(640);
            }
        );
    }
}
