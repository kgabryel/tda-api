<?php

namespace App\Alarm\Infrastructure\Request\Periodic;

use App\Alarm\Domain\Entity\NotificationTypeId;
use App\Alarm\Domain\Entity\NotificationTypesList;
use App\Shared\Domain\Config;
use App\Shared\Infrastructure\NotificationTypesUtils;
use App\Shared\Infrastructure\Request\BasicRequest;
use App\Shared\Infrastructure\Utils\UserUtils;
use Illuminate\Validation\Rule;

class AddNotificationRequest extends BasicRequest
{
    /** @var int[] */
    private array $availableTypes;

    public function __construct(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    ) {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->availableTypes = NotificationTypesUtils::getTypesIdsForUser(UserUtils::getLoggedUser());
    }

    public function rules(): array
    {
        return [
            'notificationTime' => sprintf(
                'integer|between:-%s,%s',
                Config::PERIODIC_NOTIFICATION_MAX_VALUE,
                Config::PERIODIC_NOTIFICATION_MAX_VALUE
            ),
            'time' => 'required|string|size:5',
            'interval' => 'nullable|integer',
            'intervalBehaviour' => sprintf('required|string|max:%s', Config::MAX_INTERVAL_NAME_LENGTH),
            'notificationTypes' => 'required|array|min:1',
            'notificationTypes.*' => [Rule::in($this->availableTypes)],
        ];
    }

    public function getTime(): int
    {
        return $this->notificationTime;
    }

    public function getHour(): string
    {
        return $this->time;
    }

    public function getInterval(): ?int
    {
        return $this->interval ?? null;
    }

    public function getIntervalBehaviour(): string
    {
        return $this->intervalBehaviour;
    }

    public function getNotificationTypes(): NotificationTypesList
    {
        return new NotificationTypesList(
            ...array_map(
                static fn(int $id) => new NotificationTypeId($id),
                $this->notificationTypes
            )
        );
    }
}
