<?php 

namespace LTFramework\Auth\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use LTFramework\Contracts\Notifications\EmailVerification;
use URL;

class VerifyEmail extends Notification implements EmailVerification {

    public static $toMailCallback;

    /**
     * Get the notification's channels
     * 
     * @param mixed $notifiable
     * @return array|string
     */
    public function via($notifiable) 
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }

        return (new MailMessage)
            ->view(env('LT_EMAIL_TEMPLATE','mail.email'))
            ->subject('Verifica Email')
            ->greeting('Gentile Cliente,')
            ->line('Per attivare il tuo account clicca sul pulsante qui sotto.')
            ->action('Attiva Account', $verificationUrl);
    }

    protected function verificationUrl($notifiable) {
       return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            ['id' => $notifiable->getKey()]
        );
    }
}