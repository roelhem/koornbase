<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-09-18
 * Time: 13:15
 */

namespace App\Http\GraphQLNew\Types\Models\OAuth;


class OAuthAuthCodeClientType extends OAuthClientType
{
    public $name = 'OAuthAuthCodeClient';

    public $description = "A client application that first requests the authorization of an `User`. 
                           It\'s the most safe and robust client type.";
}