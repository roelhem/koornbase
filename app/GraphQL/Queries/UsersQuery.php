<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 17:46
 */

namespace App\GraphQL\Queries;


use App\User;
use GraphQL;
use GraphQL\Type\Definition\Type;


class UsersQuery extends ModelListQuery
{


    protected $attributes = [
        'name' => 'Users query'
    ];

    protected $typeName = 'User';

    /** @inheritdoc */
    public function query($args, $selectFields)
    {
        return User::query();
    }

}