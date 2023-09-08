<?php

// phpcs:disable Symfony.Commenting.FunctionComment.Missing
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Models\Actor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tagd\Core\Models\Model;
use Tagd\Core\Models\Traits\HasUpload;
use Tagd\Core\Models\Upload\Upload;

class ResellerImage extends Model
{
    use HasFactory, HasUpload;

    public $table = 'resellers_images';

    protected $fillable = [
        'reseller_id',
        'upload_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function reseller()
    {
        return $this->belongsTo(Reseller::class);
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
