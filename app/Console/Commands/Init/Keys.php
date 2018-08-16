<?php

namespace App\Console\Commands\Init;

use App\Notifications\TestMobileMessage;
use Illuminate\Console\Command;

class Keys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:keys 
                            {--force : Overwrite the Passport keys if they already exist }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates all the keys that are needed to run the KoornBase-system properly.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->line('Generating the application key...');

        $this->call('key:generate');

        $this->line('Generating the Passport keys (for the OAuth2-server)...');

        $this->call('passport:keys', [
            '--force' => boolval($this->option('force'))
        ]);

    }
}
