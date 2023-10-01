<?php

namespace App\Notifications;

use App\Helpers\Helper;
use App\Library\Tool;
use App\Models\EmailTemplates;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Request;

class LoginConfirmation extends Notification
{
    use Queueable;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     *
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
     *
     * @return MailMessage
     */
    public function toMail($notifiable)
    {

        $template = EmailTemplates::where('slug', 'login_notification')->first();

        $subject = Tool::renderTemplate($template->subject, [
                'app_name' => config('app.name'),
        ]);

        $content = Tool::renderTemplate($template->content, [
                'app_name'   => config('app.name'),
                'time'       => Tool::customerDateTime(Carbon::now()),
                'ip_address' => Request::ip(),
        ]);

        return (new MailMessage)
                ->from(Helper::app_config('notification_email'), Helper::app_config('notification_from_name'))
                ->subject($subject)
                ->markdown('emails.customer.login', ['content' => $content]);
    }
}
