<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-06-18
 * Time: 05:53
 */

namespace Unit;


use Roelhem\RbacGraph\Contracts\Rules\RuleAttributeBag;
use Roelhem\RbacGraph\Enums\RuleAttribute;
use Roelhem\RbacGraph\Rules\CallbackBag;
use Roelhem\RbacGraph\Tests\TestCase;


class CallbackBagTest extends TestCase
{


    public function testInstance() {
        $bag = new CallbackBag();

        $this->assertInstanceOf(RuleAttributeBag::class, $bag);
        $this->assertInstanceOf(CallbackBag::class, $bag);
    }

    public function testNormalUsage() {
        $bag = new CallbackBag();

        $attr = RuleAttribute::RULE_NODE();

        $this->assertFalse($bag->has($attr));

        $bag->set($attr, 1);

        $this->assertTrue($bag->has($attr));
        $this->assertEquals(1, $bag->get($attr));

        $bag->unset($attr, 1);

        $this->assertFalse($bag->has($attr));
    }

    protected $counter = 0;

    public function testCallbackCase() {

        // init counter and callable
        $this->counter = 0;

        $callable = function() {
            $this->counter++;
            return 1;
        };

        $this->assertEquals(0, $this->counter);

        // init bag
        $bag = new CallbackBag();
        $attr = RuleAttribute::RULE_NODE();
        $bag->set($attr, $callable);

        $this->assertEquals(0, $this->counter);

        // first get.
        $this->assertEquals(1, $bag->get($attr));
        $this->assertEquals(1, $this->counter);

        // second get.
        $this->assertEquals(1, $bag->get($attr));
        $this->assertEquals(1, $this->counter);

        // Reload
        $bag->load($attr);

        $this->assertEquals(2, $this->counter);

    }

}