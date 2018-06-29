<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 29-06-18
 * Time: 23:05
 */

namespace Roelhem\RbacGraph\Services\Builders\Traits;


use Roelhem\RbacGraph\Contracts\Rules\BaseRule;
use Roelhem\RbacGraph\Contracts\Rules\DynamicRole;
use Roelhem\RbacGraph\Contracts\Rules\GateRule;
use Roelhem\RbacGraph\Contracts\Services\RuleSerializer;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Exceptions\NodeNameNotUniqueException;
use Roelhem\RbacGraph\Services\Builders\NodeBuilder;

trait ImplementBuilderRuledShortcuts
{

    /**
     * Returns the `NodeBuilder` for the node with name $name and `NodeType` $type. Creates a new `NodeBuilder`
     * if the node does not exists yet.
     *
     * @param integer|NodeType $type
     * @param string $name
     * @param array $options
     * @return NodeBuilder
     * @throws NodeNameNotUniqueException
     */
    abstract public static function node($type, string $name, $options = []);

    /**
     * Returns the array to save the rule in the option.
     *
     * @param BaseRule $rule
     * @return array
     */
    protected function getRuleOption(BaseRule $rule) {
        $serializer = resolve(RuleSerializer::class);

        if(!($serializer instanceof RuleSerializer)) {
            trigger_error('Can\'t resolve a RuleSerializer.');
        }

        return $serializer->option($rule);
    }

    /**
     * @param DynamicRole $rule
     * @param string $name
     * @return NodeBuilder
     * @throws \Roelhem\RbacGraph\Exceptions\RbacGraphException
     */
    public function dynamicRole(DynamicRole $rule, ?string $name = null) {

        if($name === null) {
            $name = $rule->defaultNodeName();
        }

        $options = [
            'rule' => $this->getRuleOption($rule)
        ];

        $for = $rule->forAuthorizableTypes();
        if(is_array($for) && count($for) > 0) {
            $options['for'] = $for;
        }

        $node = $this->node(NodeType::DYNAMIC_ROLE, $name, $options);

        $node->title($rule->defaultNodeTitle());
        $node->description($rule->defaultNodeDescription());

        return $node;
    }

    /**
     * @param string $name
     * @param GateRule $rule
     * @return NodeBuilder
     */
    public function gate(string $name, GateRule $rule) {

        $options = [
            'rule' => $this->getRuleOption($rule)
        ];

        $node = $this->node(NodeType::GATE, $name, $options);
        return $node;
    }

}