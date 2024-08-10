<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApplicationSubmitted extends Notification
{
    use Queueable;

    protected $application;

    public function __construct($application)
    {
        $this->application = $application;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Your application has been successfully submitted.')
            ->line('Application ID: ' . $this->application->id)
            ->line('Thank you for your application!');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Application Submitted',
            'message' => 'Your application with ID ' . $this->application->id . ' has been submitted.',
        ];
    }
}
