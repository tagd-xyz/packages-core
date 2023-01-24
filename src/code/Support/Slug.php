<?php

namespace Tagd\Core\Support;

use Exception;
use RangeException;

class Slug
{
    // Open Location Code alphabet - https://en.wikipedia.org/wiki/Open_Location_Code
    public const ALPHABET = '23456789CFGHJMPQRVWX';

    public const CHUNK_SIZE = 4;

    public const CHUNK_TOTAL = 4;

    public const DELIMITER = '-';

    /**
     * @var array
     */
    private $chunks = [];

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->generate();
    }

    /**
     * Generates
     *
     * @return array
     *
     * @throws RangeException
     * @throws Exception
     */
    public function generate(): array
    {
        $this->chunks = [];
        for ($i = 0; $i <= self::CHUNK_TOTAL; $i++) {
            $this->chunks[] = $this->chunk(self::CHUNK_SIZE, self::ALPHABET);
        }

        return $this->chunks;
    }

    /**
     * toString
     *
     * @return string
     */
    public function toString(): string
    {
        return implode(self::DELIMITER, $this->chunks);
    }

    /**
     * toArray
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->chunks;
    }

    /**
     * Generate a random string, using a cryptographically secure pseudorandom number generator (random_int)
     *
     * @param  int  $length
     * @param  string  $keyspace
     * @return string
     *
     * @throws RangeException
     * @throws Exception
     */
    private function chunk(
        int $length = self::CHUNK_SIZE,
        string $alphabet = self::ALPHABET
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
