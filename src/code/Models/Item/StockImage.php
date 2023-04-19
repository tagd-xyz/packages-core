<?php

// phpcs:disable Symfony.Commenting.FunctionComment.Missing
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Models\Item;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tagd\Core\Models\Model;
use Tagd\Core\Models\Traits\HasUpload;
use Tagd\Core\Models\Upload\Upload;

class StockImage extends Model
{
    use HasFactory, HasUpload;

    public $table = 'stock_images';

    protected $fillable = [
        'stock_id',
        'upload_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function stock()
    {
        return $this->belongsTo(Stock::class);
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
                    // return $sih->square(100)->focusOnFace();
                    return $sih->square(100)->fit('inside');
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
                    return $sih->square(640)->fit('inside');
                }
            );
        } else {
            return null;
        }
    }
}
