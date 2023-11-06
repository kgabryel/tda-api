<?php

namespace App\User\Application\Command\ChangeSettingsValue;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\User\Domain\Entity\SettingsKey;

/**
 * Aktualizuje wartosc ustawienia
 */
#[CommandHandler(ChangeSettingsValueHandler::class)]
class ChangeSettingsValue implements Command
{
    private SettingsKey $key;
    private mixed $value;

    public function __construct(SettingsKey $key, mixed $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function getKey(): SettingsKey
    {
        return $this->key;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}
