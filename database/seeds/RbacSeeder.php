<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17-06-18
 * Time: 00:12
 */

class RbacSeeder extends \Roelhem\RbacGraph\Seeders\RbacGraphSeeder
{

    protected $buildFiles = [
        __DIR__.'/../../rbac/roles.php',
        __DIR__.'/../../rbac/groups.php'
    ];

}