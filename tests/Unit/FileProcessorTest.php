<?php

namespace Tests\Unit;

use App\Services\File;
use PHPUnit\Framework\TestCase;

class FileProcessorTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_log_file_and_send_id_to_file_class()
    {
        $file = new File();

        $text = 'order-service - [17/Sep/2022:10:29:10] "POST /orders HTTP/1.1" 201';

        $this->assertTrue(
            $file->write('logs.txt', $text.PHP_EOL, 10)
        );

        unlink('logs.txt');
    }

    public function test_create_log_file_and_process_all_of_lines()
    {
        $file = new File();

        $text = 'order-service - [17/Sep/2022:10:29:10] "POST /orders HTTP/1.1" 201';

        $file->write('logs.txt', $text.PHP_EOL, 20);

        $count = 0;

        foreach ($file->read('logs.txt') as $value) {
            $count++;
        }

        $this->assertEquals(20, $count);

        unlink('logs.txt');
    }
}
