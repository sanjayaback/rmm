<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendOtpNotification extends Notification
{
    use Queueable;

    public function __construct(public string $otp) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Rentivo Security Code: ' . $this->otp)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for registering with Rentivo. Please use the 6-digit security code below to complete your account verification:')
            ->line('🔑 Security Code: ' . $this->otp)
            ->line('This code will expire in 10 minutes.')
            ->line('If you did not request this verification code, please ignore this email.')
            ->salutation('Best regards, The Rentivo Team');
    }
}
