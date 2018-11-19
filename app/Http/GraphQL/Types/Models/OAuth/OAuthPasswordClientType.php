<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-09-18
 * Time: 13:27
 */

namespace App\Http\GraphQL\Types\Models\OAuth;


class OAuthPasswordClientType extends OAuthClientType
{
    public $name = 'OAuthPasswordClient';

    public $description = "Represents an `OAuthClient` that is allowed authenticate users by sending the username/email 
                           and password directly from the client to the server. 
                           \n\nThis makes is possible to make nicer and easier log-in forms, but also enables the 
                           client application to steal the username and password from the user. Therefore, this type
                           of client should only be used for trusted applications.";
}