<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:14
 */

namespace App\Http\GraphQL\Queries;

use App\PersonEmailAddress;
use GraphQL\Type\Definition\Type;

class PersonEmailAddressesQuery extends ModelListQuery
{

    protected $modelClass = PersonEmailAddress::class;


    protected function filterArgs()
    {
        return array_merge(parent::filterArgs(), [

            'personId' => [
                'type' => Type::id(),
                'description' => 'Filters the contact entries that belong to the Person with the provided `ID`.'
            ],

            'index' => [
                'type' => Type::int(),
                'description' => 'Filters the contact entries with the provided index value.',
            ],

            'label' => [
                'type' => Type::string(),
                'description' => 'Filters the contact entries with a label that is like the provided string.'
            ],


        ]);
    }

}