<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 20:21
 */

namespace App\GraphQL\Fields\Stamps\Traits;

use GraphQL;

trait TimestampTrait
{

    public function type() {
        return GraphQL::type('DateTime');
    }

}