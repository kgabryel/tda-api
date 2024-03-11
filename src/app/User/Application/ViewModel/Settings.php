<?php

namespace App\User\Application\ViewModel;

use App\User\Infrastructure\StringUtils;
use JsonSerializable;

class Settings implements JsonSerializable
{
    private ?EmailState $emailState;
    private array $values;
    private string $pushNotificationKey;

    public function __construct(?EmailState $emailState = null, array $values = [], string $pushNotificationKey = '')
    {
        $this->emailState = $emailState;
        $this->values = $values;
        $this->pushNotificationKey = $pushNotificationKey;
    }

    public function setEmailState(EmailState $emailState): self
    {
        $this->emailState = $emailState;

        return $this;
    }

    public function setPushNotificationKey(string $pushNotificationKey): self
    {
        $this->pushNotificationKey = $pushNotificationKey;

        return $this;
    }

    public function addValue(string $key, mixed $value): self
    {
        $this->values[StringUtils::toCamelCase($key)] = $value;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return array_merge(
            ['pushNotificationKey' => $this->pushNotificationKey],
            $this->values,
            $this->emailState === null ? [] : $this->emailState->jsonSerialize()
        );
    }
}
