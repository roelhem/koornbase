<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-06-18
 * Time: 10:35
 */

namespace Roelhem\RbacGraph\Contracts;


use Illuminate\Routing\Route;

interface BuilderShortcuts
{

    /**
     * @param string $name
     * @return NodeBuilder
     */
    public function role(string $name);

    /**
     * @param string|null $name
     * @return NodeBuilder
     */
    public function superRole(?string $name = null);

    /**
     * @param string $name
     * @return NodeBuilder
     */
    public function abstractRole(string $name);

    /**
     * @param string $name
     * @return NodeBuilder
     */
    public function dynamicRole(string $name);

    /**
     * @param string $name
     * @return NodeBuilder
     */
    public function task(string $name);

    /**
     * @param string $name
     * @return NodeBuilder
     */
    public function permission(string $name);

    /**
     * @param string $name
     * @return NodeBuilder
     */
    public function permissionSet(string $name);

    /**
     * @param string|Route $route
     * @param string|null $name
     * @return NodeBuilder
     */
    public function routePermission( $route, ?string $name = null );

    /**
     * @param string $ability
     * @param null|string $name
     * @return NodeBuilder
     */
    public function ability( string $ability, ?string $name = null );

    /**
     * @param string $ability
     * @param string $modelClass
     * @param null|string $name
     * @return NodeBuilder
     */
    public function modelAbility(string $ability, string $modelClass, ?string $name = null );

    /**
     * @param string $modelClass
     * @param null|string $name
     * @param iterable|null $crudAbilities
     * @return NodeBuilder
     */
    public function crudAbilities(string $modelClass, ?string $name = null, ?iterable $crudAbilities = null);

}