<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-08-18
 * Time: 01:14
 */

namespace App\OAuth\Observers;


use App\Notifications\FoundSomething;
use App\OAuth\Client;

class ClientObserver
{

    public function created(Client $client)
    {


        \Auth::user()->notify(new FoundSomething($client));
    }

}