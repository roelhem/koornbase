<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-09-18
 * Time: 13:12
 */

namespace App\Http\GraphQLNew\Types\Models\OAuth;


use App\OAuth\Client;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ModelType;

abstract class OAuthClientType extends ModelType
{
    public $modelClass = Client::class;

    public function interfaces()
    {
        return array_merge([GraphQL::type('OAuthClient')], parent::interfaces());
    }

    protected $clientUsesRedirect = true;

    protected $orderable = false;

    protected $filterable = false;

    public function fields()
    {
        $res = [];

        if($this->clientUsesRedirect) {
            $res['redirect'] = [
                'description' => "A `URL` of the client-application that is used for *redirecting* after 
                                  *authentication*, according to the *OAuth2*-protocol.
                                  \n\nFor example, when a User successfully logged in at the login-page of the
                                  KoornBase-website, he is redirected to this `URL` to authorize the client-application
                                  for that user.",
                'type' => GraphQL::type('URL'),
                'importance' => 215,
            ];
        }

        return $res;
    }
}