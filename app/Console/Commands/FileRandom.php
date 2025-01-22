<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\File;

class FileRandom extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:random';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Selects a random file from database and prints its details to the console';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //get a file
        $file = File::inRandomOrder()->first();

        //check if we have any file
        if(!$file)
        {
            $this->error('No file found, please upload at least one!');
            return 1;
        }

        //show the details of the file
        $this->info('File details: '.(string)$file);
        return 0;
    }
}
