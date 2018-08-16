<?php

namespace App\Notifications;

use App\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword implements ShouldQueue
{

    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        $message = new MailMessage();

        $emailParam = '';

        if($notifiable instanceof User) {
            $message->greeting('Beste '.$notifiable->name_display,',');
            $emailParam = '?email='.urlencode($notifiable->email);
        }

        $message
            ->subject('Wachtwoord Herstellen')
            ->line('Je ontvangt deze E-mail omdat er een nieuw wachtwoord is aangevraagd (via de *wachtwoord vergeten?* pagina).')
            ->action('Nieuw wachtwoord instellen', url(config('app.url').route('password.reset', $this->token, false)).$emailParam)
            ->line('Als jij geen nieuw wachtwoord hebt aangevraagd, kun je deze e-mail negeren.');

        return $message;
    }

}
