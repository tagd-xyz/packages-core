<?php

namespace Tagd\Core\Models\Traits;

use Tagd\Core\Support\Sih\Sih;

trait HasSih
{
    /**
     * Gets a public url for the server image handler
     */
    public function applySih(string $s3FullPath, callable $callback): string
    {
        $sih = new Sih(
            config('tagd.aws.sih.url'),
            config('tagd.aws.s3.bucket'),
            $s3FullPath
        );

        $sih = $callback($sih);

        return $sih->url();
    }
}
