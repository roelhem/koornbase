<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 15:22
 */

namespace Feature\PathFinders;


use Roelhem\RbacGraph\Contracts\BelongsToGraph;
use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\Path;
use Roelhem\RbacGraph\Contracts\PathFinder;
use Roelhem\RbacGraph\Database\Node;
use Roelhem\RbacGraph\Graphs\DictionaryGraph;
use Roelhem\RbacGraph\PathFinders\RecursivePathFinder;
use Roelhem\RbacGraph\Tests\TestCase;

class PathFinderCommonTests extends TestCase
{

    public static function setUpBeforeClass()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUpBeforeClass();

        self::$graph = new DictionaryGraph();
        $b = self::$graph->builder();


        $b->role('Admin')->assign(
            $b->role('SubAdmin1'),
            $b->role('SubAdmin2'),
            $b->role('SubAdmin3'),
            $b->role('SubAdmin4')
        );


        $b->role('Role1')->assignTo('SubAdmin1');
        $b->role('Role2')->assignTo('SubAdmin2');
        $b->role('Role3')->assignTo('SubAdmin3');
        $b->role('Role4')->assignTo('SubAdmin4');

        $b->role('Role5')->assignTo('Admin');

        $b->role('ParentRole')->assign('Role1','Role2','Role3','Role4', 'Role5');

        $b->abstractRole('AbstractRole5a')->assignTo('Role5');
        $b->abstractRole('AbstractRole5b')->assignTo('Role5');

        $b->abstractRole('AbstractRole12')->assignTo('Role1','Role2');


        $b->task('Task1')->assignTo('AbstractRole12');
        $b->task('Task2')->assignTo('Role2');
        $b->task('Task3')->assignTo('Role3')->assign(
            $b->task('SubTask3a'),
            $b->task('SubTask3b')
        );
        $b->task('Task4')->assignTo('Role4', 'AbstractRole5a');
        $b->task('Task5')->assignTo('AbstractRole5b');

        $b->permissionSet('pSet1')->assignTo('Task1')->assign(
            $b->permission('p11'),
            $b->permission('p12'),
            $b->modelAbility('a11', Node::class),
            $b->modelAbility('a12', Node::class)
        );

        $b->crudAbilities(Node::class, 'crud2', ['a','b','c','d'])->assignTo('Task2');

        $b->get('crud2.a')->assignTo('SubTask3a');
        $b->get('crud2.b')->assignTo('SubTask3b');
        $b->get('crud2.c')->assignTo('SubTask3a','SubTask3b');

        $b->permissionSet('pSet4')->assignTo('Task4')->assign(
            $b->permissionSet('pSubSet4')->assign(
                $b->permissionSet('pSubSubSet4')->assign(
                    $b->permission('p4')
                )
            )
        );

    }

    public static $graph;

    /**
     * Provider for all the different PathFinders.
     */
    public function pathFinderProvider() {
        return [
            'RECURSIVE_PATH_FINDER' => [
                function(Graph $graph) {
                    return new RecursivePathFinder($graph);
                }
            ]
        ];
    }

    /**
     * @param callable $constructor
     * @dataProvider pathFinderProvider
     */
    public function testInitialize($constructor) {
        $graph = new DictionaryGraph();

        $finder = $constructor($graph);

        $this->assertInstanceOf(BelongsToGraph::class, $finder);
        $this->assertInstanceOf(PathFinder::class, $finder);
        $this->assertTrue($graph->contains($finder));
    }

    /**
     * @param callable $constructor
     * @dataProvider pathFinderProvider
     * @throws
     */
    public function testPathExists($constructor) {
        $finder = $constructor(self::$graph);

        if(!($finder instanceof PathFinder)) {
            throw new \LogicException;
        }

        $this->assertTrue($finder->exists('Admin','SubAdmin1'));
        $this->assertTrue($finder->exists('Admin','p11'));
        $this->assertTrue($finder->exists('Role2','a11'));
        $this->assertTrue($finder->exists('p4', 'p4'));
        $this->assertTrue($finder->exists('Role4', 'p4'));
        $this->assertTrue($finder->exists('Role5', 'p4'));

        $this->assertFalse($finder->exists('SubAdmin1','Admin'));
        $this->assertFalse($finder->exists('Role1','Role2'));
        $this->assertFalse($finder->exists('Admin','ParentRole'));
        $this->assertFalse($finder->exists('crud2.a','crud2.b'));
        $this->assertFalse($finder->exists('SubTask3a','crud2.b'));
        $this->assertFalse($finder->exists('Task3','crud2'));
        $this->assertFalse($finder->exists('Role4','p12'));
    }

    /**
     * @param callable $constructor
     * @dataProvider pathFinderProvider
     * @throws
     */
    public function testPathCount($constructor) {
        $finder = $constructor(self::$graph);

        if(!($finder instanceof PathFinder)) {
            throw new \LogicException;
        }

        $this->assertEquals(0, $finder->count('Admin','ParentRole'));
        $this->assertEquals(1, $finder->count('crud2','crud2'));
        $this->assertEquals(1, $finder->count('SubAdmin4','p4'));
        $this->assertEquals(2, $finder->count('Task3','crud2.c'));
        $this->assertEquals(0, $finder->count('crud2.c','Task3'));
        $this->assertEquals(1, $finder->count('Task3','crud2.a'));
        $this->assertEquals(2, $finder->count('ParentRole','p4'));

    }

    /**
     * @param callable $constructor
     * @dataProvider pathFinderProvider
     * @throws
     */
    public function testFindPath($constructor) {
        $finder = $constructor(self::$graph);

        if(!($finder instanceof PathFinder)) {
            throw new \LogicException;
        }

        $this->assertNull($finder->find('AbstractRole5a','AbstractRole5b'));
        $this->assertNull($finder->find('Role5','crud2.d'));
        $this->assertNull($finder->find('Task1','Role1'));

        $pathA = $finder->find('Role1','Role1');
        $this->assertInstanceOf(Path::class, $pathA);
        $this->assertCount(1,$pathA);
        $this->assertTrue($pathA->hasNode('Role1'));
        $this->assertFalse($pathA->hasNode('Role2'));

        $pathB = $finder->find('SubAdmin4','p4');
        $this->assertInstanceOf(Path::class, $pathB);
        $this->assertCount(7, $pathB);
        $this->assertTrue($pathB->hasNode('SubAdmin4'));
        $this->assertEquals(0, $pathB->getNodeIndex('SubAdmin4'));
        $this->assertTrue($pathB->hasNode('p4'));
        $this->assertEquals(6, $pathB->getNodeIndex('p4'));
    }

    /**
     * @param callable $constructor
     * @dataProvider pathFinderProvider
     * @throws
     */
    public function testFindAllPaths($constructor) {
        $finder = $constructor(self::$graph);

        if(!($finder instanceof PathFinder)) {
            throw new \LogicException;
        }

        $this->assertCount(0, $finder->findAll('Admin','ParentRole'));
        $this->assertCount(0, $finder->findAll('crud2.c','Task3'));

        $pathsA = $finder->findAll('Task3','crud2.c');
        $this->assertCount(2, $pathsA);
        foreach ($pathsA as $pathA) {
            $this->assertCount(3, $pathA);
            $this->assertEquals(0, $pathA->getNodeIndex('Task3'));
            $this->assertEquals(2, $pathA->getNodeIndex('crud2.c'));
        }
    }

}