<?php

namespace Tagd\Core\Support\Sih\Transforms\Native;

trait Resize
{
    /**
     * Square
     */
    public function square(int $size): self
    {
        return $this->width($size)->height($size);
    }

    /**
     * Width
     */
    public function widthAndHeight(int $width, int $height): self
    {
        return $this->width($width)->height($height);
    }

    /**
     * Width
     */
    public function width(int $width): self
    {
        $this->payload['edits']['resize']['width'] = $width;

        return $this;
    }

    /**
     * Height
     */
    public function height(int $height): self
    {
        $this->payload['edits']['resize']['height'] = $height;

        return $this;
    }

    /**
     * Fit
     */
    public function fit(string $fit): self
    {
        $this->payload['edits']['resize']['fit'] = $fit->value;

        return $this;
    }

    /**
     * Background
     *
     * @param  int  $r
     * @param  int  $g
     * @param  int  $b
     */
    public function background(
        int $red = 255,
        int $green = 255,
        int $blue = 255,
        float $alpha = 1.0
    ): self {
        $this->payload['edits']['resize']['background'] = [
            'r' => $red,
            'g' => $green,
            'b' => $blue,
            'alpha' => $alpha,
        ];

        return $this;
    }
}
