<?php

namespace App\Task\Infrastructure\Request\Periodic;

use App\Alarm\Application\Command\PeriodicAlarm\AddNotification\NotificationDto;
use App\Alarm\Application\Command\PeriodicAlarm\Create\Notifications;
use App\Alarm\Domain\Entity\NotificationTypeId;
use App\Alarm\Domain\Entity\NotificationTypesList;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\IntervalType;
use App\Shared\Domain\Config;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Infrastructure\NotificationTypesUtils;
use App\Shared\Infrastructure\Request\BasicRequest;
use App\Shared\Infrastructure\Utils\UserUtils;
use App\Task\Application\Command\PeriodicTask\Create\AlarmDto;
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
            'task' => 'required|array',
            'task.name' => sprintf('required|string|filled|max:%s', Config::TASK_NAME_LENGTH),
            'task.content' => 'nullable|string',
            'task.interval' => 'required|integer|min:1',
            'task.intervalType' => ['required', 'string', Rule::in($this->intervalTypes)],
            'task.start' => 'required|date',
            'task.stop' => 'nullable|date|after:start',
            'task.catalogs' => 'present|array',
            'task.catalogs.*' => sprintf('integer|exists:catalogs,id,user_id,%s', $this->userId),
            'task.bookmarks' => 'present|array',
            'task.bookmarks.*' => sprintf('string|exists:bookmarks,id,user_id,%s', $this->userId),
            'task.notes' => 'present|array',
            'task.notes.*' => sprintf('string|exists:notes,id,user_id,%s', $this->userId),
            'task.files' => 'present|array',
            'task.files.*' => sprintf('string|exists:files,id,user_id,%s', $this->userId),
            'task.videos' => 'present|array',
            'task.videos.*' => sprintf('string|exists:videos,id,user_id,%s', $this->userId),
            'alarm' => 'nullable|array',
            'alarm.name' => sprintf('required_with:alarm|string|filled|max:%s', Config::ALARM_NAME_LENGTH),
            'alarm.content' => 'nullable|string',
            'alarm.notifications' => 'required_with:alarm|array|min:1',
            'alarm.notifications.*.notificationTime' => sprintf(
                'required|integer|between:-%s,%s',
                Config::PERIODIC_NOTIFICATION_MAX_VALUE,
                Config::PERIODIC_NOTIFICATION_MAX_VALUE
            ),
            'notifications.*.time' => 'required|string|size:5',
            'notifications.*.interval' => 'nullable|integer',
            'notifications.*.intervalBehaviour' => sprintf('required|string|max:%s', Config::MAX_INTERVAL_NAME_LENGTH),
            'alarm.notifications.*.notificationTypes' => 'required|array|min:1',
            'alarm.notifications.*.notificationTypes.*' => [Rule::in($this->availableTypes)],
            'alarm.catalogs' => 'sometimes|present|array',
            'alarm.catalogs.*' => sprintf('integer|exists:catalogs,id,user_id,%s', $this->userId)
        ];
    }

    public function getCatalogs(): array
    {
        return $this->task['catalogs'];
    }

    public function getVideos(): array
    {
        return $this->task['videos'];
    }

    public function getBookmarks(): array
    {
        return $this->task['bookmarks'];
    }

    public function getNotes(): array
    {
        return $this->task['notes'];
    }

    public function getFiles(): array
    {
        return $this->task['files'];
    }

    public function getName(): string
    {
        return $this->task['name'];
    }

    public function getText(): ?string
    {
        return StringUtils::trimContent($this->task['content']);
    }

    public function getInterval(): int
    {
        return $this->task['interval'];
    }

    public function getIntervalType(): IntervalType
    {
        return IntervalType::from($this->task['intervalType']);
    }

    public function getStart(): DateTimeImmutable
    {
        return (new Carbon($this->task['start']))->toDateTimeImmutable();
    }

    public function getStop(): ?DateTimeImmutable
    {
        if ($this->task['stop'] === null) {
            return null;
        }

        return (new Carbon($this->task['stop']))->toDateTimeImmutable();
    }

    public function getAlarmDto(string $alarmId): ?AlarmDto
    {
        if (!$this->hasAlarm()) {
            return null;
        }

        return new AlarmDto(
            new AlarmsGroupId($alarmId),
            $this->alarm['name'],
            StringUtils::trimContent($this->alarm['content']),
            new CatalogsIdsList(
                ...array_map(static fn(string $id) => new CatalogId($id), $this->alarm['catalogs'])
            ),
            new Notifications(
                new AlarmsGroupId($alarmId),
                ...$this->getNotifications($alarmId)
            )
        );
    }

    private function hasAlarm(): bool
    {
        return $this->alarm !== null;
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
            $this->alarm['notifications']
        );
    }
}
