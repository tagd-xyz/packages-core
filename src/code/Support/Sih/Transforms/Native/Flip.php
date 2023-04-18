<?php

namespace Tagd\Core\Support\Sih\Transforms\Native;

trait Flip
{
    /**
     * alias for flop
     */
    public function mirror(): self
    {
        return $this->flop();
    }

    /**
     * alias for flop
     */
    public function flipVertical(): self
    {
        return $this->flop();
    }

    /**
     * alias for flip
     */
    public function flipHorizontal(): self
    {
        return $this->flip();
    }

    /**
     * Flip
     */
    public function flip(): self
    {
        $this->payload['edits']['flip'] = true;

        return $this;
    }

    /**
     * Flop
     */
    public function flop(): self
    {
        $this->payload['edits']['flop'] = true;

        return $this;
    }
}
