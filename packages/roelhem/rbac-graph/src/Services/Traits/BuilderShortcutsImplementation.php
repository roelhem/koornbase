<?php

namespace Roelhem\RbacGraph\Services\Traits;


use Roelhem\RbacGraph\Contracts\Rules\GateRule;
use Roelhem\RbacGraph\Contracts\Services\Builder;
use Roelhem\RbacGraph\Contracts\Rules\DynamicRole;

trait BuilderShortcutsImplementation
{

    /**
     * @return Builder
     */
    abstract public function builder();

    public function role(string $name) {
        return $this->builder()->role($name);
    }

    public function superRole(?string $name = null) {
        return $this->builder()->superRole($name);
    }

    public function abstractRole(string $name) {
        return $this->builder()->abstractRole($name);
    }

    public function dynamicRole(DynamicRole $rule, ?string $name = null) {
        return $this->builder()->dynamicRole($rule, $name);
    }

    public function task(string $name) {
        return $this->builder()->task($name);
    }

    public function permission(string $name) {
        return $this->builder()->permission($name);
    }

    public function permissionSet(string $name) {
        return $this->builder()->permissionSet($name);
    }

    public function routePermission( $route, ?string $name = null ) {
        return $this->builder()->routePermission($route, $name);
    }

    public function actionPermission( $action, ?string $name = null ) {
        return $this->builder()->actionPermission($action, $name);
    }

    public function ability( string $ability, ?string $name = null ) {
        return $this->builder()->ability($ability, $name);
    }

    public function modelAbility(string $ability, string $modelClass, ?string $name = null ) {
        return $this->builder()->modelAbility($ability, $modelClass, $name);
    }

    public function crudAbilities(string $modelClass, ?string $name = null, ?iterable $crudAbilities = null ) {
        return $this->builder()->crudAbilities($modelClass, $name, $crudAbilities);
    }

    public function gate(string $name, GateRule $rule) {
        return $this->builder()->gate($name, $rule);
    }
}