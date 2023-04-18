<?php

namespace Tagd\Core\Support\Sih\Transforms\Native;

trait Color
{
    /**
     * alias for grayscale
     */
    public function blackAndWhite(): self
    {
        return $this->grayscale();
    }

    /**
     * Grayscale
     */
    public function grayscale(): self
    {
        $this->payload['edits']['grayscale'] = true;

        return $this;
    }

    /**
     * alias for negate
     */
    public function negative(): self
    {
        return $this->negate();
    }

    /**
     * Negate
     */
    public function negate(): self
    {
        $this->payload['edits']['negate'] = true;

        return $this;
    }

    /**
     * Tint
     *
     * @param  int  $r
     * @param  int  $g
     * @param  int  $b
     */
    public function tint(int $red = 255, int $green = 255, int $blue = 255): self
    {
        $this->payload['edits']['tint'] = [
            'r' => $red,
            'g' => $green,
            'b' => $blue,
        ];

        return $this;
    }

    /**
     * Normalise
     */
    public function normalise(): self
    {
        $this->payload['edits']['normalise'] = true;

        return $this;
    }
}
