<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-08-18
 * Time: 03:54
 */

namespace App\OAuth\Notifications;


use App\OAuth\Client;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class ClientCreatedNotification extends Notification implements ShouldQueue
{

    use Queueable;

    /**
     * @var Client
     */
    protected $client;

    /**
     * ClientCreatedNotification constructor.
     * @param Client $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    public function via($notifiable)
    {
        return ['slack','database'];
    }

    public function toArray() {
        return [
            'title' => 'Nieuwe OAuth-client aangemaakt.',
            'client_id' => $this->client->id,
            'created_at' => $this->client->created_at,
            'created_by' => $this->client->created_by,
        ];
    }


    public function toSlack() {

        return (new SlackMessage())
            ->info()
            ->content('Er is een nieuwe OAuth-client aangemaakt op de KoornBase!')
            ->attachment(function(SlackAttachment $attachment) {
                $attachment->title("\"{$this->client->name}\"", 'https://koornbase.test/dashboard#/oauth/clients/'.$this->client->id)
                    ->fields([
                        'van' => $this->client->user->name_display,
                        'type' => '_'.$this->client->type->getName().'_'
                    ])
                    ->timestamp($this->client->created_at)
                    ->footer('Aangemaakt door _'.$this->creatorName().'_')
                    ->color('#C96DD8');
            });
    }

    protected function creatorName() {
        $user = User::find($this->client->created_by);
        if($user === null) {
            return null;
        } else {
            return $user->name_display;
        }
    }

}