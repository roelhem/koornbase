<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 16:15
 */

namespace App\OAuth;

use Laravel\Passport\PersonalAccessClient as PassportPersonalAccessClient;


/**
 * App\OAuth\PersonalAccessClient
 *
 * @property-read \App\OAuth\Client $client
 * @mixin \Eloquent
 */
class PersonalAccessClient extends PassportPersonalAccessClient
{


}