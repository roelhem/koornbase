<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-09-18
 * Time: 13:37
 */

namespace App\Http\GraphQLNew\Types\Models\OAuth;


class OAuthPersonalClientType extends OAuthClientType
{
    public $name = 'OAuthPersonalClient';

    public $description = "An `OAuthClient` that enables *personal access* of users using this client. The
                           access-tokens can be generated on the server-side for any user, which makes it a lot
                           easier to provide access to single users.
                           \n\nThe main usages of *personal access clients* is for development, when a client-app
                           doesn't have an user-interface that handles authentication yet.";
}