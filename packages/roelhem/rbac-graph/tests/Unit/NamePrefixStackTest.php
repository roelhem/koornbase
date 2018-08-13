<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 12:06
 */

namespace Unit;


use Roelhem\RbacGraph\Contracts\BelongsToGraph;
use Roelhem\RbacGraph\Graphs\DictionaryGraph;
use Roelhem\RbacGraph\Helpers\NamePrefixStack;
use Roelhem\RbacGraph\Tests\TestCase;

class NamePrefixStackTest extends TestCase
{

    public function testInstance() {
        $graph = new DictionaryGraph();
        $prefixStack = new NamePrefixStack($graph);

        $this->assertInstanceOf(NamePrefixStack::class, $prefixStack);
        $this->assertInstanceOf(BelongsToGraph::class, $prefixStack);
    }

    public function testPushCount() {
        $graph = new DictionaryGraph();
        $prefixStack = new NamePrefixStack($graph);

        $this->assertCount(0, $prefixStack);

        $prefixStack->push('a.');

        $this->assertCount(1, $prefixStack);

        $prefixStack->push('b.');
        $prefixStack->push('c.');

        $this->assertCount(3, $prefixStack);

        $this->assertEquals('c.', $prefixStack->pop());
        $this->assertEquals('b.', $prefixStack->pop());
        $this->assertEquals('a.', $prefixStack->pop());

        $this->assertCount(0, $prefixStack);
    }

    public function testPrefixValue() {
        $graph = new DictionaryGraph();
        $prefixStack = new NamePrefixStack($graph);

        $this->assertEquals('', $prefixStack->prefix());
        $this->assertEquals('test', $prefixStack->prefix('test'));

        $prefixStack->push('a.');
        $prefixStack->push('b.');

        $this->assertEquals('a.b.', $prefixStack->prefix());
        $this->assertEquals('a.b.test', $prefixStack->prefix('test'));
        $this->assertEquals('test', $prefixStack->prefix('test', 0));
        $this->assertEquals('a.test', $prefixStack->prefix('test', 1));

        $prefixStack->pop();

        $this->assertEquals('a.test', $prefixStack->prefix('test'));
    }

}