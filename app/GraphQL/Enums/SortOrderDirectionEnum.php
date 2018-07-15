<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 05:28
 */

namespace App\GraphQL\Enums;


use Rebing\GraphQL\Support\Type as GraphQLType;

class SortOrderDirectionEnum extends GraphQLType
{

    const ASC = 'asc';
    const DESC = 'desc';

    protected $enumObject = true;

    public function attributes()
    {
        return [
            'name' => 'SortOrderDirection',
            'description' => 'The direction of a SortOrder object.',
            'values' => [
                'ASC' => self::ASC,
                'DESC' => self::DESC
            ]
        ];
    }

}