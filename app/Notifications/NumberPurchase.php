<?php

namespace App\Notifications;

use App\Helpers\Helper;
use App\Library\Tool;
use App\Models\EmailTemplates;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NumberPurchase extends Notification
{

    use Queueable;

    protected string $number_url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($number_url)
    {
        $this->number_url = $number_url;
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

        $template = EmailTemplates::where('slug', 'keyword_purchase_notification')->first();

        $subject = Tool::renderTemplate($template->subject, [
                'app_name' => config('app.name'),
        ]);

        $content = Tool::renderTemplate($template->content, [
                'app_name'    => config('app.name'),
                'number_url' => "<a href='$this->number_url' target='_blank'>".__('locale.labels.number')."</a>",
        ]);

        return (new MailMessage)
                ->from(Helper::app_config('notification_email'), Helper::app_config('notification_from_name'))
                ->subject($subject)
                ->markdown('emails.number.purchase', ['content' => $content, 'url' => $this->number_url]);
    }
}
