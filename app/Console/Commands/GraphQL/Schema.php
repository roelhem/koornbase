<?php

namespace App\Console\Commands\GraphQL;

use GraphQL\Utils\SchemaPrinter;
use Illuminate\Console\Command;

class Schema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'graphql:schema
                            {--save : saves the schema to a file }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gives the current GraphQL Schema in a GraphQL-format.';

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
        $this->comment('Retrieving the Schema...');

        // Get the schema
        $schema = \GraphQL::schema();

        // Get the schema as a (GraphQL-schema formatted) string.
        $this->comment('Computing the GraphQL-schema string in the right format...');
        $string = SchemaPrinter::doPrint($schema);

        if($this->option('save')) {
            $this->comment('Saving the file...');

            // Determine the right filename.
            $filename = 'schema.graphqls';
            $path = base_path($filename);
            // Save the file
            file_put_contents($path, $string);

            // Send to the user
            $this->info("Successfully saved the schema in the file `$filename`.");
        } else {
            echo PHP_EOL, $string, PHP_EOL;
        }

    }
}
