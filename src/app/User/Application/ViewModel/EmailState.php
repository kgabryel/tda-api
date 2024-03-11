<?php

namespace App\User\Application\ViewModel;

use JsonSerializable;

class EmailState implements JsonSerializable
{
    private ?string $email;
    private EmailStateValue $state;

    public function __construct(?string $email, bool $confirmed)
    {
        $this->email = $email;

        if ($this->email === null) {
            $this->state = EmailStateValue::EMAIL_EMPTY;
        } elseif (!$confirmed) {
            $this->state = EmailStateValue::EMAIL_UNCONFIRMED;
        } else {
            $this->state = EmailStateValue::EMAIL_CONFIRMED;
        }
    }

    public function jsonSerialize(): array
    {
        return [
            'email' => $this->email,
            'emailState' => $this->state
        ];
    }
}
