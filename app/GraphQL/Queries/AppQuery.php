<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 16:39
 */

namespace App\GraphQL\Queries;


use App\OAuth\App;

class AppQuery extends ModelViewQuery
{

    protected $modelClass = App::class;
    protected $slug = true;

}