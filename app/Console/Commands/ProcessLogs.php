<?php

namespace App\Console\Commands;

use App\Services\File;
use App\Services\formats\LogFormat;
use Generator;
use Illuminate\Console\Command;

class ProcessLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bugloos:process-log {file=storage/logs/logs.txt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Converting file to save to the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $file = base_path($this->argument('file'));

        if (!file_exists($file)) {
            $this->error('file does not exist');
        }

        $fileProcess = new File();

        foreach ($fileProcess->read($file) as $key => $value) {
            $logFormatter = new LogFormat($value);

            $logFormatter->formatted();
        }


        return Command::SUCCESS;
    }


}
