<?php

namespace App\Mail;

use App\Http\Controllers\HelperController;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class sendToUser extends Mailable
{
    use Queueable, SerializesModels;
    protected $extension;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($extension)
    {
        $this->extension = $extension;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $base64 = new HelperController();

        $url    = $base64->base64url_encode(json_encode([
            'expire'            => time() + 3600,
            'user_id'           => auth()->user()->id,
            'user_uid'          => auth()->user()->uid,
            'exten_id'          => $this->extension->id,
            'exten_uid'         => $this->extension->uid,
        ]));

        return $this->from(auth()->user()->email, 'Invite Agent')
            ->subject('Add new agent')
            ->markdown('emails.customer.inviteAgent', [
                'user'      => auth()->user(),
                'data'      => $this->extension,
                'url'       => url('/add-agent') . '/' . $url
            ]);
    }
}
