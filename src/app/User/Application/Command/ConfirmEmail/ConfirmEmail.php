<?php

namespace App\User\Application\Command\ConfirmEmail;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;

/**
 * Potawierdza e-mail
 */
#[CommandHandler(ConformEmailHandler::class)]
class ConfirmEmail implements Command
{
    private string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
