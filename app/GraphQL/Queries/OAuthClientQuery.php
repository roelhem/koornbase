<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 01-08-18
 * Time: 08:25
 */

namespace App\GraphQL\Queries;


use App\OAuth\Client;

class OAuthClientQuery extends ModelViewQuery
{

    protected $modelClass = Client::class;

    protected function getTypeName()
    {
        return 'OAuthClient';
    }

}