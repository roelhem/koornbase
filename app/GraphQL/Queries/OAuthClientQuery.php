<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 16:39
 */

namespace App\GraphQL\Queries;


use App\OAuth\Client;

class OAuthClientQuery extends ModelListQuery
{

    protected $modelClass = Client::class;



    protected function getTypeName()
    {
        return 'OAuthClient';
    }



    protected function filterArgs()
    {
        return array_merge(parent::filterArgs(), [




        ]);
    }

}