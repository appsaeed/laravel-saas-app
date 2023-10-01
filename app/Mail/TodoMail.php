<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TodoMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var $data
     * @property string $subject
     * @property string $subject
     */
    protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $subject = isset($this->data['subject']) ? $this->data['subject'] : 'no subject';
        $message = isset($this->data['message']) ? $this->data['message'] : 'no message';
        $taskurl = isset($this->data['taskurl']) ? $this->data['taskurl'] : '';


        return $this->from(config('mail.from.address'))
            ->subject($subject)
            ->markdown('emails.customer.todos.create', [
                'message'       => $message,
                'url'           => $taskurl
            ]);
    }
}
