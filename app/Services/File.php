<?php

namespace App\Services;

use Exception;
use Generator;

class File
{
    /**
     * read
     *
     * @param  string  $file
     * @return Generator
     */
    public function read(string $file): Generator
    {
        $file = fopen($file, 'r');

        try {
            while ($line = fgets($file, 4096)) {
                yield $line;
            }
        } finally {
            fclose($file);
        }
    }

    /**
     * write
     *
     * @param  string  $file
     * @param  string  $text
     * @param  int  $length
     * @return bool
     */
    public function write(string $file, string $text, int $length = 100): bool
    {
        $file = fopen($file, "w");
        try {
            for ($i = 0; $i < $length; $i++) {
                fwrite($file, $text);
            }
        } catch (Exception $exception) {
            return false;
        } finally {
            fclose($file);

            return true;
        }
    }
}
