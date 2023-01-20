<?php

namespace App\Console\Commands;

use App\Models\Log;
use App\Services\File;
use App\Services\formats\LogFormat;
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

        try {
            foreach ($fileProcess->read($file) as $value) {
                Log::saveLog(new LogFormat($value));
            }
        } finally {
            $this->info('The command was successful!');
        }

        return Command::SUCCESS;
    }


}
