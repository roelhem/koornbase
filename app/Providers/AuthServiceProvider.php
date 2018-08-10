<?php

namespace App\Providers;

use App\Enums\OAuthScope;
use App\OAuth\AuthCode;
use App\OAuth\Client;
use App\OAuth\PersonalAccessClient;
use App\OAuth\Token;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {

        Passport::useAuthCodeModel(AuthCode::class);
        Passport::useClientModel(Client::class);
        Passport::usePersonalAccessClientModel(PersonalAccessClient::class);
        Passport::useTokenModel(Token::class);

        Passport::routes();

        Passport::tokensCan(OAuthScope::getScopeArray());


        //
    }
}
