<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 20:30
 */

namespace App\GraphQL\Fields\Stamps\Traits;

use GraphQL;

trait UserstampRelationTrait
{

    public function type()
    {
        return GraphQL::type('User');
    }

}