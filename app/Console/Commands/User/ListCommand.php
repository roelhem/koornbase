<?php

namespace App\Console\Commands\User;

use App\User;
use Illuminate\Console\Command;

class ListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gives a list of all users in the database.';

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
        $headers = ['ID', 'Username','E-mail Address','Created at','Person ID'];
        $users = User::all(['id','name','email','created_at','person_id'])->toArray();

        $this->table($headers, $users);
    }
}
