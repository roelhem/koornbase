<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 03:04
 */

namespace App\Http\GraphQL\Fields\Relations;


use Rebing\GraphQL\Support\Field;
use Roelhem\RbacGraph\Services\RbacQueryFilter;

class PersonField extends Field
{


    public function attributes()
    {
        return [
            'name' => 'person',
            'description' => 'The Person where this object belongs to.',
            'query' => RbacQueryFilter::eagerLoadingContraintGraphQLClosure(),
        ];
    }

    public function type()
    {
        return \GraphQL::type('Person');
    }

}