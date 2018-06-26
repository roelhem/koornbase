<?php

namespace Roelhem\RbacGraph\Seeders;



use Illuminate\Database\Seeder;

/**
 * Class RbacGraphSeeder
 *
 * @package Roelhem\RbacGraph\Seeders
 */
class RbacGraphSeeder extends Seeder
{

    /**
     * An array of filenames that contain the build-logic for the RbacGraph.
     *
     * @var string[]
     */
    protected $buildFiles = [];

    /**
     * Returns an array of filenames that contain the build-logic for the RbacGraph.
     *
     * You should overwrite this method if you need a more complicated picking of files.
     *
     * @return string[]
     */
    protected function buildFiles()
    {
        return $this->buildFiles;
    }

    /**
     * Builds the graph.
     */
    protected function build() {

        // run the files
        foreach ($this->buildFiles() as $buildFile) {
            require($buildFile);
        }


    }

    /**
     * Runs the seeder.
     */
    public function run() {
        $this->build();
    }

}