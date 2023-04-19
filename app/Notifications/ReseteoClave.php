<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class ReseteoClave extends ResetPassword
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

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
        if (request()->identificacion!=null) {
            return (new MailMessage)
            ->subject(Lang::get('Bienvenido!!!'))
            ->line('Bienvenido a nuestro equipo , '.request()->nombre.' por favor es neceario que ingereses al link de abajo, para establecer una nueva contraseña
            para que puedas hacer uso de nuestro sistema.')
            ->line('Haz clic en el botón que aparece a continuación para cambiar tu contraseña.')
            ->action('Cambiar Contraseña', url('https://admin.adnfirmas.com/session/reset-password?token='.$this->token.'&email='.$notifiable->getEmailForPasswordReset()))
            ->line(Lang::get('Este enlace solo es valido dentro de los próximos :count minutos.',['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line('Gracias por usar nuestra aplicacion!');
        } else {
            return (new MailMessage)
        ->subject(Lang::get('Cambio de contraseña'))
        ->line('Se solicitó un restablecimiento de contraseña para tu email '.$notifiable->getEmailForPasswordReset().', haz clic en el botón que aparece a continuación para cambiar tu contraseña.')
        ->action('Cambiar Contraseña', url('https://admin.adnfirmas.com/session/reset-password?token='.$this->token.'&email='.$notifiable->getEmailForPasswordReset()))
        ->line('Si tu no realizaste la solicitud de cambio de contraseña, solo ignora este mensaje.')
        ->line(Lang::get('Este enlace solo es valido dentro de los próximos :count minutos.',['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
        ->line('Gracias por usar nuestra aplicacion!');
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
