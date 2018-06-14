<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $errorMessage;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.importError')
            ->subject('Error Occurred While Uploading CSV File.')
            ->with([
                'errorMessage' => $this->errorMessage
            ]);
    }
}
