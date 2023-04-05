<?php

namespace Tagd\Core\Support;

use Exception;
use RangeException;

class Slug
{
    // Open Location Code alphabet - https://en.wikipedia.org/wiki/Open_Location_Code
    public const ALPHABET_OPEN_LOCATION = '23456789CFGHJMPQRVWX';

    public const ALPHABET_DIGITS_ONLY = '0123456789';

    private $chunkSize;

    private $chunkTotal;

    private $delimiter;

    private $alphabet;

    /**
     * @var array
     */
    private $chunks = [];

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(
        string $alphabet = self::ALPHABET_OPEN_LOCATION,
        int $chunkSize = 4,
        int $chunkTotal = 4,
        string $delimiter = '-',
    ) {
        $this->alphabet = $alphabet;
        $this->chunkSize = $chunkSize;
        $this->chunkTotal = $chunkTotal;
        $this->delimiter = $delimiter;
        $this->generate();
    }

    /**
     * Generates
     *
     *
     * @throws RangeException
     * @throws Exception
     */
    public function generate(): array
    {
        $this->chunks = [];
        for ($i = 0; $i < $this->chunkTotal; $i++) {
            $this->chunks[] = $this->chunk($this->chunkSize, $this->alphabet);
        }

        return $this->chunks;
    }

    /**
     * toString
     */
    public function toString(): string
    {
        return implode($this->delimiter, $this->chunks);
    }

    /**
     * toArray
     */
    public function toArray(): array
    {
        return $this->chunks;
    }

    /**
     * Generate a random string, using a cryptographically secure pseudorandom number generator (random_int)
     *
     * @param  string  $keyspace
     *
     * @throws RangeException
     * @throws Exception
     */
    private function chunk(
        int $length,
        string $alphabet
    ): string {
        if ($length < 1) {
            throw new \RangeException('Length must be a positive integer');
        }
        $pieces = [];
        $max = mb_strlen($alphabet, '8bit') - 1;
        for ($i = 0; $i < $length; $i++) {
            $pieces[] = $alphabet[random_int(0, $max)];
        }

        return implode('', $pieces);
    }
}
