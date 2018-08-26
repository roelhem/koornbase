<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 16:39
 */

namespace App\Http\GraphQL\Queries;


use App\OAuth\App;

class AppsQuery extends ModelListQuery
{

    protected $modelClass = App::class;



    protected function filterArgs()
    {
        return array_merge(parent::filterArgs(), [




        ]);
    }

}