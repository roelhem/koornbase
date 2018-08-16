<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-08-18
 * Time: 03:54
 */

namespace App\OAuth\Notifications;


use App\OAuth\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class ClientCreatedNotification extends Notification
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
        ];
    }

    public function toSlack() {
        return (new SlackMessage())
            ->content('Er is een nieuwe OAuth-client aangemaakt op de KoornBase!')
            ->attachment(function(SlackAttachment $attachment) {
                $attachment->title($this->client->name)
                    ->fields([
                        'type' => $this->client->type,
                        'eigenaar' => $this->client->user->name_display,
                        'aangemaakt op' => $this->client->created_at,
                        'aangemaakt door' => $this->client->creator()->name_display,
                    ]);
            });
    }

}