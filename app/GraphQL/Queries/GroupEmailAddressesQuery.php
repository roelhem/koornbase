<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:13
 */

namespace App\GraphQL\Queries;

use App\GroupEmailAddress;
use GraphQL\Type\Definition\Type;

class GroupEmailAddressesQuery extends ModelListQuery
{

    protected $modelClass = GroupEmailAddress::class;


    protected function filterArgs()
    {
        return array_merge(parent::filterArgs(), [

            'groupId' => [
                'type' => Type::id(),
                'description' => 'Filters all the emailAddresses that belong to the group with the provided id.'
            ],



        ]);
    }


}