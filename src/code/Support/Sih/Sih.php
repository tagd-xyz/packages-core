<?php

namespace Tagd\Core\Support\Sih;

class Sih implements Transforms\Native\Definitions
{
    use Transforms\Native\Color,
        Transforms\Native\Crop,
        Transforms\Native\Flip,
        Transforms\Native\Resize;

    private $cdn;

    private $payload;

    /**
     * Constructor
     */
    public function __construct(
        string $cdn,
        string $bucket,
        string $key
    ) {
        // sanitize params
        $cdn = trim(rtrim($cdn, '/'));
        $bucket = trim($bucket);
        $key = trim(rtrim($key, '/'));

        // validate params
        if ($cdn == '' || $bucket == '' || $key == '') {
            throw new Exceptions\InvalidConfig();
        }

        // set payload
        $this->cdn = $cdn;
        $this->payload = [
            'bucket' => $bucket,
            'key' => $key,
            'edits' => [
                'resize' => [],
            ],
        ];
    }

    /**
     * Builds the CDN url with edits applied
     */
    public function url(): string
    {
        return $this->cdn . '/' . \base64_encode(\json_encode($this->payload));
    }
}
