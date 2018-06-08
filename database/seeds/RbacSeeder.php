<?php

use Illuminate\Database\Seeder;
use \App\Services\Rbac\RbacGenerator;

class RbacSeeder extends Seeder
{

    /**
     * @var RbacGenerator
     */
    protected $generator;

    /**
     * RbacSeeder constructor.
     *
     * @param RbacGenerator $generator
     */
    public function __construct(RbacGenerator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->generator->run();
    }
}
