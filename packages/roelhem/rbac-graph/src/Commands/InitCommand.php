<?php

namespace Roelhem\RbacGraph\Commands;


use Illuminate\Console\Command;
use Illuminate\Console\OutputStyle;
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
        $this->runBuildingFiles();

        $this->info("Building the main graph using the temporary building graph...");

        $builder = \Rbac::builder();
        $graph = \Rbac::graph();

        if($graph instanceof MutableGraph) {
            $builder->build($graph);
            $this->info("Successfully initialized the graph!");
        } else {
            $this->error("The graph that is used for authorization is not a mutable graph.");
        }

    }


    /**
     * Returns the graph-building files.
     *
     * @return array
     */
    protected function getBuildingFiles() {
        $res = [];

        if($this->option('example')) {
            $res = array_merge($res, [
                realpath(__DIR__ . '/../../rbac-example/roles.php'),
                realpath(__DIR__ . '/../../rbac-example/permissions.php'),
                realpath(__DIR__ . '/../../rbac-example/tasks.php')
            ]);
        }

        return $res;
    }


    /**
     * Runs the graph-building files.
     */
    protected function runBuildingFiles() {
        $this->info("Retrieving the graph-building php-files...");

        $files = collect($this->getBuildingFiles());
        $maxStrLen = $files->max(function($file) {
            return strlen($file);
        });

        $this->info("Running the {$files->count()} files with the default RBAC-Graph Builder...");

        $nodeCount = 0;
        $edgeCount = 0;

        foreach ($files as $file) {
            $this->output->write('  > '. str_pad($file, $maxStrLen + 3),false);
            require($file);

            $builder = \Rbac::builder();
            $newNodeCount = $builder->getNodes()->count();
            $newEdgeCount = $builder->getEdges()->count();

            $diffNodeCount = $newNodeCount - $nodeCount;
            $diffEdgeCount = $newEdgeCount - $edgeCount;

            $diffNodeString = str_pad($diffNodeCount, 3, ' ', STR_PAD_LEFT);
            $diffEdgeString = str_pad($diffEdgeCount, 3, ' ', STR_PAD_LEFT);

            $this->line(" <comment>"."[ Found </comment>$diffNodeString<comment> nodes and </comment>$diffEdgeString<comment> edges ]</comment>  ");

            $nodeCount = $newNodeCount;
            $edgeCount = $newEdgeCount;
        }

        $this->line("<info>Loaded </info>$nodeCount<info> nodes and </info>$edgeCount<info> edges into the tempolary graph.</info>");
    }
}