<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 09:43
 */

namespace App\Services\Rbac;
use App\Contracts\Rbac\RbacService;


/**
 * Class AbstractServiceComponent
 *
 * An abstract class for the basis of Rbac service components.
 *
 * @package App\Services\Rbac
 */
abstract class AbstractServiceComponent
{

    /**
     * @var RbacService
     */
    protected $service;

    /**
     * AbstractServiceComponent constructor.
     *
     * @param RbacService $service
     */
    public function __construct(RbacService $service)
    {
        $this->service = $service;
    }

}