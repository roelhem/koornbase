<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 25-06-18
 * Time: 02:16
 */

namespace Feature;


use Roelhem\RbacGraph\Database\DatabaseGraph;
use Roelhem\RbacGraph\Database\DatabasePathFinder;
use Roelhem\RbacGraph\Database\Node;
use Roelhem\RbacGraph\Database\Path;
use Roelhem\RbacGraph\Tests\TestCase;

class PathTest extends TestCase
{

    /**
     * @throws
     */
    public function testPaths() {

        $graph = resolve(DatabaseGraph::class);

        if(!($graph instanceof DatabaseGraph)) {
            $this->assertFalse(true);
        }


        $finder = new DatabasePathFinder();



        $this->assertTrue($finder->exists('Admin', 'models:group-email-addresses:crud.view'));
        $this->assertEquals(3, $finder->count('Admin', 'models:group-email-addresses:crud.view'));

        echo $finder->find('Admin', 'models:group-email-addresses:crud.view');

        echo PHP_EOL;
        $finder->findAll('Admin', 'models:group-email-addresses:crud.view')->each(function($path) {
            echo PHP_EOL.$path;
        });
    }

}