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
use App\OAuth\Notifications\ClientCreatedNotification;

class ClientObserver
{

    public function created(Client $client)
    {
        $user = $client->user;
        if($user !== null) {
            $user->notify(new ClientCreatedNotification($client));
        }
    }

}