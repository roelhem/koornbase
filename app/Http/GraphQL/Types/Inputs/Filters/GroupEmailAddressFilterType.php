<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-09-18
 * Time: 13:21
 */

namespace App\Http\GraphQL\Types\Inputs\Filters;


use GraphQL\Type\Definition\Type;

class GroupEmailAddressFilterType extends FilterType
{

    public function filters()
    {
        return [
            'groupId' => [
                'type' => Type::id(),
                'description' => 'Filters all the emailAddresses that belong to the group with the provided id.'
            ],
        ];
    }

}