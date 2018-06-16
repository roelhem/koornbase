<?php

namespace Roelhem\RbacGraph\Commands;


use Illuminate\Console\Command;
use Roelhem\RbacGraph\Contracts\MutableGraph;

class InitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rbac-graph:init {--example}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Builds the initial graph.';


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

        if($this->option('example')) {
            require(__DIR__ . '/../../rbac-example/roles.php');
            require(__DIR__ . '/../../rbac-example/permissions.php');
            require(__DIR__ . '/../../rbac-example/tasks.php');
        }

        $graph = \Rbac::graph();
        $builder = \Rbac::builder();

        if($graph instanceof MutableGraph) {
            $builder->build($graph);
        } else {
            $this->error("The graph that is used for authorization is not a mutable graph.");
        }

    }
}