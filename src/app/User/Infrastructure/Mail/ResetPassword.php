<?php

namespace App\User\Infrastructure\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable;
    use SerializesModels;

    private string $token;
    private string $lang;

    public function __construct(string $token, string $lang)
    {
        $this->token = $token;
        $this->lang = $lang;
    }

    public function build(): ResetPassword
    {
        return $this->view('reset-password', [
            'token' => $this->token,
            'lang' => $this->lang
        ]);
    }
}
