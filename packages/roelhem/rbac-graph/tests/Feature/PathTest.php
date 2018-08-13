<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 25-06-18
 * Time: 02:16
 */

namespace Feature;


use App\Group;
use App\Person;
use App\User;
use Roelhem\RbacGraph\Database\DatabaseGraph;
use Roelhem\RbacGraph\Database\Node;
use Roelhem\RbacGraph\Tests\TestCase;

class PathTest extends TestCase
{

    public function testDatabaseGraph() {

        $graph = resolve(DatabaseGraph::class);

        if(!($graph instanceof DatabaseGraph)) {
            $this->assertFalse(true);
        } else {
            $this->assertTrue(true);
        }

        $graph->getPotentialDynamicRoles(Person::class)->each(function($node) {
            echo ' + '.$node.PHP_EOL;
        });

        $graph->getPotentialDynamicRoles(User::class)->each(function($node) {
            echo ' + '.$node.PHP_EOL;
        });

    }

}