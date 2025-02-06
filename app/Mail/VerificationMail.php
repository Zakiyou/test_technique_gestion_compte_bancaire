<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;

class VerificationMail extends Mailable
{
    public $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function build()
    {
        return $this->subject('Code de vérification')
                    ->view('emails.verification')
                    ->with(['code' => $this->code]);
    }
}
