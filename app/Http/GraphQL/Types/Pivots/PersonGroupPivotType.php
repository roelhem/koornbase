<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 19/11/2018
 * Time: 20:29
 */

namespace App\Http\GraphQL\Types\Pivots;


use App\Pivots\PersonGroup;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\PivotType;

class PersonGroupPivotType extends PivotType
{
    protected $pivotClass = PersonGroup::class;

    /**
     * Returns the definitions of the fields of this Type.
     *
     * @return array
     */
    protected function fields()
    {
        return [
            'group' => [
                'type' => GraphQL::type('Group'),
            ],
            'person' => [
                'type' => GraphQL::type('Person'),
            ]
        ];
    }
}