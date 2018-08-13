<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 01:26
 */

namespace App\GraphQL\Queries;


use Rebing\GraphQL\Support\Query;

class MeQuery extends Query
{

    protected $attributes = [
        'name' => 'me',
        'description' => 'Gives the user of the current request or `null` if no user is associated with this request.'
    ];

    public function type()
    {
        return \GraphQL::type('User');
    }

    public function resolve()
    {
        return \Auth::user();
    }

}