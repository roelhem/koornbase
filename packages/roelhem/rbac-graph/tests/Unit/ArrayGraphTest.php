<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 06:20
 */

namespace Roelhem\RbacGraph\Tests\Unit;


use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Graphs\ArrayGraph;
use Roelhem\RbacGraph\Tests\TestCase;

class ArrayGraphTest extends TestCase
{

    public function testInstance() {

        $graph = new ArrayGraph();

        $this->assertInstanceOf(Graph::class, $graph);

    }

}