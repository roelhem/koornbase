<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-06-18
 * Time: 00:09
 */

namespace Unit;


use Roelhem\RbacGraph\Contracts\Services\RuleSerializer;
use Roelhem\RbacGraph\Rules\StaticRule;
use Tests\TestCase;

class RuleSerializerTest extends TestCase
{

    /**
     * @return RuleSerializer
     */
    protected function getSerializer() {
        return resolve(RuleSerializer::class);
    }

    /**
     * Tests if the serializer is a proper serializer.
     *
     * @return void
     */
    public function testInstance() {
        $this->assertInstanceOf(RuleSerializer::class, $this->getSerializer());
    }

    /**
     * Tests if the serializer can serialize a StaticRule rule.
     *
     * @return void
     */
    public function testOnStaticRule() {
        $serializer = $this->getSerializer();

        $trueRule = new StaticRule(true);
        $falseRule = new StaticRule(false);

        $this->assertInstanceOf(StaticRule::class, $trueRule);
        $this->assertInstanceOf(StaticRule::class, $falseRule);
        $this->assertTrue($trueRule->getValue());
        $this->assertFalse($falseRule->getValue());

        $trueRuleOption = $serializer->option($trueRule);
        $falseRuleOption = $serializer->option($falseRule);

        $this->assertTrue(is_array($trueRuleOption));
        $this->assertTrue(is_array($falseRuleOption));

        $trueRuleNew = $serializer->rule($trueRuleOption);
        $falseRuleNew = $serializer->rule($falseRuleOption);

        $this->assertInstanceOf(StaticRule::class, $trueRuleNew);
        $this->assertInstanceOf(StaticRule::class, $falseRuleNew);
        $this->assertTrue($trueRuleNew->getValue());
        $this->assertFalse($falseRuleNew->getValue());
    }

}