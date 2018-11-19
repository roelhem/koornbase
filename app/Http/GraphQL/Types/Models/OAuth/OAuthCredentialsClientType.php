<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-09-18
 * Time: 13:43
 */

namespace App\Http\GraphQL\Types\Models\OAuth;


class OAuthCredentialsClientType extends OAuthClientType
{
    public $name = 'OAuthCredentialsClient';

    public $description = "An `OAuthClient` that enables *machine-to-machine* communication without the need of a
                           `User`.";

    protected $clientUsesRedirect = false;
}