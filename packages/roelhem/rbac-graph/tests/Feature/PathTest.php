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
use Roelhem\RbacGraph\Contracts\Authorizable;
use Roelhem\RbacGraph\Database\DatabaseAuthorizer;
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
        } else {
            $this->assertTrue(true);
        }


        Group::query()->each(function(Group $group) use ($graph) {
            echo PHP_EOL.$group->id.' : '.$group->name.PHP_EOL;
            $graph->getEntryNodes($group)->each(function($node) {
                echo '    '.$node.PHP_EOL;
            });
        });


        echo PHP_EOL.PHP_EOL;

        $user = User::query()->where('id', '=',2)->first();

        if(!($user instanceof User)) {
            $this->assertFalse(true);
        }

        $user->getAuthorizableGroups()->each(function(Person $a) use ($graph) {
            echo get_class($a)."  [{$a->id}: {$a->name}]".PHP_EOL;

            $graph->getEntryNodes($a)->each(function($node) {
                echo '    '.$node.PHP_EOL;
            });

            $a->getAuthorizableGroups()->each(function(Group $b) use ($graph) {
                echo '  + '.get_class($b)."  [{$b->id}: {$b->name}]".PHP_EOL;

                $graph->getEntryNodes($b)->each(function($node) {
                    echo '        '.$node.PHP_EOL;
                });
            });
        });

        echo PHP_EOL.PHP_EOL;

        $graph->getEntryNodes($user)->each(function($node) {
            echo $node.PHP_EOL;
        });
    }

}