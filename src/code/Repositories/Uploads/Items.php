<?php

namespace Tagd\Core\Repositories\Uploads;

use Tagd\Core\Models\Upload\Collection as UploadCollection;
use Tagd\Core\Models\Upload\Upload as Model;
use Tagd\Core\Repositories\Interfaces\Uploads\Items as ItemsInterface;
use Tagd\Core\Support\Repository\Exceptions\GenericException;
use Tagd\Core\Support\Repository\Repository;

class Items extends Repository implements ItemsInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Constructor.
     */
    public function __construct(Model $model)
    {
        parent::__construct($model);
    }

    /**
     * Creates a new upload for an image
     */
    public function image(string $itemId, string $fileName): Model
    {
        $s3Bucket = config('tagd.aws.s3.bucket');
        if (is_null($s3Bucket)) {
            throw new GenericException('Missing S3 bucket config');
        }

        $folder = trim(rtrim((UploadCollection::ITEM_IMAGES)->value), '/');

        return parent::create([
            'bucket' => $s3Bucket,
            'file' => $fileName,
            'folder' => $folder . '/' . $itemId,
            'entity_id' => $itemId,
            'entity_type' => 'item',
        ]);

    }
}
