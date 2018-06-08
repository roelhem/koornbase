<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 09:01
 */

namespace App\Contracts\Rbac;

/**
 * Contract RbacService
 *
 * A contract for services that handle the Rbac structure.
 *
 * @package App\Contracts\Rbac
 */
interface RbacService extends RbacBuilder, RbacProvider, RbacRequestHandler
{

    /**
     * Returns the RbacBuilder that belong to this RbacService
     *
     * @return RbacBuilder
     */
    public function builder() : RbacBuilder;

    /**
     * Returns the RbacProvider that belong to this RbacService
     *
     * @return RbacProvider
     */
    public function provider() : RbacProvider;

    /**
     * Returns the RbacRequestHandler that belong to this RbacService
     *
     * @return RbacRequestHandler
     */
    public function handler() : RbacRequestHandler;

    /**
     * Returns the RbacAuthorizers that belong to this RbacService
     *
     * @return RbacAuthorizer
     */
    public function authorizer() : RbacAuthorizer;

}