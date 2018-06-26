<?php

namespace Roelhem\RbacGraph\Facades;


use Illuminate\Support\Facades\Facade;
use Roelhem\RbacGraph\Contracts\Services\RbacService;

/**
 * Class Rbac
 *
 * The facade for the RbacService.
 *
 * @package Roelhem\RbacGraph\Facades
 */
class Rbac extends Facade
{

    protected static function getFacadeAccessor()
    {
        return RbacService::class;
    }

}