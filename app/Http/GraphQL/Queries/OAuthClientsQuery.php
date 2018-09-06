<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 16:39
 */

namespace App\Http\GraphQL\Queries;


use App\OAuth\Client;
use GraphQL\Type\Definition\Type;

class OAuthClientsQuery extends ModelListQuery
{

    protected $typeName = 'OAuthClient';

}