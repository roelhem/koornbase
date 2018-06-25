<?php

namespace Roelhem\RbacGraph\Builders\Traits;

use Illuminate\Routing\Route;
use Roelhem\RbacGraph\Contracts\Builder;
use Roelhem\RbacGraph\Contracts\NodeBuilder;
use Roelhem\RbacGraph\Contracts\Rules\DynamicRole;
use Roelhem\RbacGraph\Enums\NodeType;

trait ImplementBuilderShortcuts
{

    /**
     * @param NodeType|integer $type
     * @param string $name
     * @return NodeBuilder
     */
    public abstract function node($type, string $name);

    /**
     * @param string $name
     * @return NodeBuilder
     */
    public function role(string $name) {
        return $this->node(NodeType::ROLE, $name);
    }

    /**
     * @param string $name
     * @return NodeBuilder
     */
    public function superRole(string $name = null) {
        $type = NodeType::SUPER_ROLE;
        if($name === null) {
            $type = NodeType::by($type);
            $name = $type->conf('default-values.name');
        }
        return $this->node($type, $name);
    }

    /**
     * @param string $name
     * @return NodeBuilder
     */
    public function abstractRole(string $name) {
        return $this->node(NodeType::ABSTRACT_ROLE, $name);
    }

    /**
     * @param DynamicRole $rule
     * @param string $name
     * @return NodeBuilder
     */
    public function dynamicRole(DynamicRole $rule, ?string $name = null) {

        if($name === null) {
            $name = $rule->defaultNodeName();
        }

        $options = [];
        $options['rule'] = [];
        $options['rule']['constr'] = $rule->constructor();
        $attrs = $rule->constructorAttributes();
        if(is_array($attrs) && count($attrs) > 0) {
            $options['rule']['constrAttrs'] = $attrs;
        }

        $for = $rule->forAuthorizableTypes();
        if(is_array($for) && count($for) > 0) {
            $options['for'] = $for;
        }

        $node = $this->node(NodeType::DYNAMIC_ROLE, $name, $options);

        $node->title($rule->defaultNodeTitle());
        $node->description($rule->defaultNodeDescription());
    }

    /**
     * @param string $name
     * @return NodeBuilder
     */
    public function task(string $name) {
        return $this->node(NodeType::TASK, $name);
    }

    /**
     * @param string $name
     * @return NodeBuilder
     */
    public function permission(string $name) {
        return $this->node(NodeType::PERMISSION, $name);
    }

    /**
     * @param string $name
     * @return NodeBuilder
     */
    public function permissionSet(string $name) {
        return $this->node(NodeType::PERMISSION_SET, $name);
    }

    /**
     * @param string|Route $route
     * @param string|null $name
     * @return NodeBuilder
     */
    public function routePermission( $route, ?string $name = null ) {
        $type = NodeType::ROUTE_PERMISSION;
        if($route instanceof Route) {
            $route = $route->getName();
        }

        if($name === null) {
            $type = NodeType::get($type);
            $name = $type->conf('default-values.name-prefix').$route;
        }

        return $this->node(NodeType::ROUTE_PERMISSION, $name, [
            'route' => $route
        ]);
    }

    /**
     * @param string $ability
     * @param null|string $name
     * @return NodeBuilder
     */
    public function ability( string $ability, ?string $name = null ) {
        if($name === null) {
            $name = $ability;
        }
        return $this->node(NodeType::ABILITY, $name, [
            'ability' => $ability
        ]);
    }

    /**
     * @param string $ability
     * @param string $modelClass
     * @param null|string $name
     * @return NodeBuilder
     */
    public function modelAbility(string $ability, string $modelClass, ?string $name = null ) {
        if($name === null) {
            $name = $ability;
        }
        return $this->node(NodeType::MODEL_ABILITY, $name, [
            'ability' => $ability,
            'modelClass' => $modelClass
        ]);
    }

    /**
     * @param string $modelClass
     * @param iterable|null $crudAbilities
     * @param null|string $name
     * @return NodeBuilder
     */
    public function crudAbilities(string $modelClass, ?string $name = null, ?iterable $crudAbilities = null) {
        $type = NodeType::get(NodeType::CRUD_ABILITY_SET);
        if($crudAbilities === null) {
            $crudAbilities = $type->conf('default-values.crud-abilities', ['view','create','update','delete']);
        }
        if($name === null) {
            $name = $type->conf('default-values.name', 'crud-abilities');
        }
        $prefix = $name.$type->conf('default-values.delimiter','.');

        $abilitySet = $this->node(NodeType::CRUD_ABILITY_SET, $name, [
            'modelClass' => $modelClass,
            'prefix' => $prefix,
            'abilities' => $crudAbilities
        ]);

        $this->group($prefix, function(Builder $builder) use ($crudAbilities, $modelClass, $abilitySet) {
            foreach ($crudAbilities as $key => $value) {
                if(is_string($key)) {
                    $abilityNode = $builder->modelAbility($key, $modelClass, $value);
                } else {
                    $abilityNode = $builder->modelAbility($value, $modelClass);
                }
                $abilityNode->assignTo($abilitySet);
            }
        });

        return $abilitySet;
    }
}