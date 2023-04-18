<?php

// phpcs:disable Symfony.Commenting.FunctionComment.Missing
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Models\Traits;

trait HasUpload
{
    use HasSih;

    // /**
    //  * get urlSmall attribute
    //  *
    //  * @return string
    //  */
    // public function getTransformedUploadUrl(
    //     string $relation,
    //     callable $transformation
    // ): ?string {
    //     if ($this->$relation) {
    //         return $this->applySih(
    //             $this->$relation->fullPath,
    //             $transformation
    //         );
    //     } else {
    //         return null;
    //     }
    // }

    /**
     * transform image url
     *
     * @return string
     */
    public function getTransformedUploadUrl(
        string $fullPath,
        callable $transformation
    ): ?string {
        return $this->applySih(
            $fullPath,
            $transformation
        );
    }
}
