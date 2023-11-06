<?php

namespace App\User\Infrastructure\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Confirm extends Mailable
{
    use Queueable;
    use SerializesModels;

    private string $code;
    private string $lang;

    public function __construct(string $code, string $lang)
    {
        $this->code = $code;
        $this->lang = $lang;
    }

    public function build(): Confirm
    {
        return $this->view('confirm-email', [
            'code' => $this->code,
            'lang' => $this->lang
        ]);
    }
}
