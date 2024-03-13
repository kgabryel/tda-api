<?php

namespace App\Alarm\Infrastructure\Request\Periodic;

use App\Alarm\Application\Command\PeriodicAlarm\AddNotification\NotificationDto;
use App\Alarm\Domain\Entity\NotificationTypeId;
use App\Alarm\Domain\Entity\NotificationTypesList;
use App\Shared\Application\IntervalType;
use App\Shared\Domain\Config;
use App\Shared\Infrastructure\NotificationTypesUtils;
use App\Shared\Infrastructure\Request\BasicRequest;
use App\Shared\Infrastructure\Utils\UserUtils;
use App\User\Infrastructure\StringUtils;
use Carbon\Carbon;
use DateTimeImmutable;
use Illuminate\Validation\Rule;

class CreateRequest extends BasicRequest
{
    /** @var int[] */
    private array $availableTypes;
    /** @var string[] */
    private array $intervalTypes;

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
        $this->intervalTypes = IntervalType::getValues();
    }

    public function rules(): array
    {
        return [
            'name' => sprintf('required|string|filled|max:%s', Config::ALARM_NAME_LENGTH),
            'content' => 'nullable|string',
            'notifications' => 'required|array|min:1',
            'notifications.*.date' => sprintf(
                'integer|between:-%s,%s',
                Config::PERIODIC_NOTIFICATION_MAX_VALUE,
                Config::PERIODIC_NOTIFICATION_MAX_VALUE
            ),
            'notifications.*.time' => 'required|string|size:5',
            'notifications.*.interval' => 'nullable|integer',
            'notifications.*.intervalBehaviour' => sprintf('required|string|max:%s', Config::MAX_INTERVAL_NAME_LENGTH),
            'notifications.*.notificationTypes' => 'required|array|min:1',
            'notifications.*.notificationTypes.*' => [Rule::in($this->availableTypes)],
            'interval' => 'required|integer|min:1',
            'intervalType' => ['required', 'string', Rule::in($this->intervalTypes)],
            'start' => 'required|date',
            'stop' => 'nullable|date|after:start',
            'catalogs' => 'present|array',
            'catalogs.*' => sprintf('integer|exists:catalogs,id,user_id,%s', $this->userId)
        ];
    }

    public function getCatalogs(): array
    {
        return $this->catalogs;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getText(): ?string
    {
        return StringUtils::trimContent($this->get('content'));
    }

    public function getInterval(): int
    {
        return $this->interval;
    }

    public function getIntervalType(): IntervalType
    {
        return IntervalType::from($this->intervalType);
    }

    public function getStart(): DateTimeImmutable
    {
        return (new Carbon($this->start))->toDateTimeImmutable();
    }

    public function getStop(): ?DateTimeImmutable
    {
        if ($this->stop === null) {
            return null;
        }

        return (new Carbon($this->stop))->toDateTimeImmutable();
    }

    public function getNotifications(): array
    {
        return array_map(
            static fn(array $notification) => new NotificationDto(
                $notification['notificationTime'],
                new NotificationTypesList(
                    ...array_map(
                        static fn(int $id) => new NotificationTypeId($id),
                        $notification['notificationTypes']
                    )
                ),
                $notification['time'],
                $notification['intervalBehaviour'],
                $notification['interval'] ?? null
            ),
            $this->notifications
        );
    }
}
