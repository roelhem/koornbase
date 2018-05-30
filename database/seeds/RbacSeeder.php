<?php

use Illuminate\Database\Seeder;
use \App\Role;

class RbacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require(__DIR__.'/../rbac/system.php');
        require(__DIR__.'/../rbac/groups.php');
    }
}
