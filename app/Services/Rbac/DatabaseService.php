<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 11:17
 */

namespace App\Services\Rbac;


use App\Services\Rbac\Authorizers\DepthFirstAuthorizer;

class DatabaseService extends AbstractService
{

    public function __construct()
    {
        $this->authorizer = new DepthFirstAuthorizer($this);
        $this->builder = new DatabaseBuilder($this);
        $this->provider = new DatabaseProvider($this);
        $this->handler = new DatabaseRequestHandler($this);
    }

}