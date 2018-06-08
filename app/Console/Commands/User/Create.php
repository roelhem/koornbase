<?php

namespace App\Console\Commands\User;

use App\User;
use Illuminate\Console\Command;

class Create extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create
                            {name? : The name for the new user }
                            {email? : The email address for the new user }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new user';

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
     * @throws
     */
    public function handle()
    {

        $attrs = [];

        $attrs['name'] = $this->argument('name');
        if($attrs['name'] === null) {
            $attrs['name'] = $this->ask("What should be the name?");
        }

        $attrs['email'] = $this->argument('email');
        if($attrs['email'] === null) {
            $attrs['email'] = $this->ask("What should be the email address?");
        }

        $attrs['password'] = $this->secret("What should be the password?");

        $validator = \Validator::make($attrs, [
            'name' => 'required|string|unique:users|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:8',
        ]);

        if($validator->passes()) {

            $user = new User();
            $user->name = $attrs['name'];
            $user->email = $attrs['email'];
            $user->password = bcrypt($attrs['password']);

            $user->saveOrFail();

            $this->info("The new user was successfully created!");
            $this->line("id:       {$user->id}");
            $this->line("name:     {$user->name}");
            $this->line("email:    {$user->email}");

        } else {

            $this->error("Can't create this user!", 'a');

            foreach($validator->errors()->getMessages() as $message) {
                $this->line($message);
            };

        }


    }
}
