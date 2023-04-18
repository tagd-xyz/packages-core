<?php

namespace Tagd\Core\Support\Sih\Transforms\Native;

trait Crop
{
    /**
     * focusOnFace
     */
    public function focusOnFace(): self
    {
        $width = $this->payload['edits']['resize']['width'] ?? 250;

        return $this->smartCrop(0, $width);
    }

    /**
     * smartCrop
     */
    public function smartCrop(int $faceIndex = 0, int $padding = 0): self
    {
        $this->payload['edits']['smartCrop'] = [
            'faceIndex' => $faceIndex,
            'padding' => $padding,
        ];

        return $this;
    }

    /**
     * alias for roundCrop
     */
    public function circle(int $x, int $y, int $top, int $left): self
    {
        return $this->roundCrop($x, $y, $top, $left);
    }

    /**
     * roundCrop
     */
    public function roundCrop(int $x, int $y, int $top, int $left): self
    {
        $this->payload['edits']['roundCrop'] = [
            'rx' => $x,
            'ry' => $y,
            'top' => $top,
            'left' => $left,
        ];

        return $this;
    }
}
