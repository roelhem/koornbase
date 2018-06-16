<?php

namespace Roelhem\RbacGraph\Services\Traits;


use Roelhem\RbacGraph\Contracts\Builder;

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

    public function dynamicRole(string $name) {
        return $this->builder()->dynamicRole($name);
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

    public function ability( string $ability, ?string $name = null ) {
        return $this->builder()->ability($ability, $name);
    }

    public function modelAbility(string $ability, string $modelClass, ?string $name = null ) {
        return $this->builder()->modelAbility($ability, $modelClass, $name);
    }

    public function crudAbilities(string $modelClass, ?string $name = null, ?iterable $crudAbilities = null ) {
        return $this->builder()->crudAbilities($modelClass, $name, $crudAbilities);
    }
}