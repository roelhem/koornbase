<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 20:23
 */

namespace App\GraphQL\Fields\Stamps\Traits;

use GraphQL\Type\Definition\Type;

trait UserstampIdTrait
{

    public function type() {
        return Type::id();
    }

}