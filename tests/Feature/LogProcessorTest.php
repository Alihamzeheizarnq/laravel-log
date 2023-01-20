<?php

namespace Tests\Feature;

use App\Models\Log;
use App\Services\File;
use App\Services\formats\LogFormat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogProcessorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_add_some_logs_to_test_log_formatter()
    {
        $file = new File();

        $text = 'order-service - [17/Sep/2022:10:29:10] "POST /orders HTTP/1.1" 201';

        $this->assertTrue(
            $file->write('logs.txt', $text.PHP_EOL, 1)
        );

        foreach ($file->read('logs.txt') as $value) {
            $logFormatter = new LogFormat($value);

            $this->assertArrayHasKey('name', $logFormatter->formatted());
            $this->assertArrayHasKey('created_at', $logFormatter->formatted());
            $this->assertArrayHasKey('method', $logFormatter->formatted());
            $this->assertArrayHasKey('path', $logFormatter->formatted());
            $this->assertArrayHasKey('protocol', $logFormatter->formatted());
            $this->assertArrayHasKey('status', $logFormatter->formatted());
        }

        unlink('logs.txt');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_add_some_logs_to_test_the_table_of_logs()
    {
        $file = new File();

        $text = 'order-service - [17/Sep/2022:10:29:10] "POST /orders HTTP/1.1" 201';

        $file->write('logs.txt', $text.PHP_EOL, 10);

        foreach ($file->read('logs.txt') as $value) {
            $logFormatter = new LogFormat($value);

            Log::saveLog($logFormatter);
        }

        $this->assertEquals(10, Log::all()->count());

        unlink('logs.txt');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_to_get_correct_values()
    {
        $file = new File();

        $text = 'order-service - [17/Sep/2022:10:29:10] "POST /orders HTTP/1.1" 201';

        $file->write('logs.txt', $text.PHP_EOL, 10);

        foreach ($file->read('logs.txt') as $value) {
            $logFormatter = new LogFormat($value);

            Log::saveLog($logFormatter);
        }


        $response = $this->get('/api/logs/count');

        $this->assertArrayHasKey('count', $response->original);
        $this->assertEquals(10, $response->original['count']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_the_api_for_query_string()
    {
        $file = new File();

        $textOne = 'order-service - [17/Sep/2022:10:29:10] "POST /orders HTTP/1.1" 201';
        $textTwo = 'order-service - [17/Sep/2022:10:29:10] "POST /orders HTTP/1.1" 200';

        $file->write('logs.txt', $textOne.PHP_EOL, 5);
        $file->write('logs.txt', $textTwo.PHP_EOL, 5);

        foreach ($file->read('logs.txt') as $value) {
            $logFormatter = new LogFormat($value);

            Log::saveLog($logFormatter);
        }


        $response = $this->get('/api/logs/count?statusCode=200');

        $this->assertArrayHasKey('count', $response->original);
        $this->assertEquals(5, $response->original['count']);
    }

}
