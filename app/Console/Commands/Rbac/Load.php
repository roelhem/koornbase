<?php

namespace App\Console\Commands\Rbac;

use App\Permission;
use App\Role;
use App\Services\Rbac\RbacGenerator;
use Illuminate\Console\Command;

class Load extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rbac:load {--reload}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loads the RBAC-tree into the database';


    /**
     * @var RbacGenerator
     */
    protected $generator;

    /**
     * Create a new command instance.
     *
     * @param RbacGenerator $generator
     * @return void
     */
    public function __construct(RbacGenerator $generator)
    {
        $this->generator = $generator;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        if($this->option('reload')) {
            Permission::query()->delete();
            Role::doesntHave('users')->doesntHave('groups')->doesntHave('groupCategories')->delete();
        }

        $this->generator->run();
    }
}
