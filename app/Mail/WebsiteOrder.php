<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WebsiteOrder extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $description;
    public $attachment;

    /**
     * Create a new message instance.
     *
     * @param string $subject
     * @param string $description
     * @param string|null $attachment
     * @return void
     */
    public function __construct($subject, $description, $attachment = null)
    {
        $this->subject = $subject;
        $this->description = $description;
        $this->attachment = $attachment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('WebsiteOrderMail')
                      ->subject($this->subject);
        if ($this->attachment) {
            $email->attach($this->attachment, [ 
                'as' => 'attachment.xlsx',
                'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        }

        return $email;
    }
}
?>
