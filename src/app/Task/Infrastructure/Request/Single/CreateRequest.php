<?php

namespace App\Task\Infrastructure\Request\Single;

use App\Alarm\Application\Command\SingleAlarm\Create\AlarmDto as ImAlarmDto;
use App\Alarm\Application\Command\SingleAlarm\Create\Notification;
use App\Alarm\Application\Command\SingleAlarm\Create\Notifications;
use App\Alarm\Domain\Entity\NotificationTypeId;
use App\Alarm\Domain\Entity\NotificationTypesList;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Domain\Config;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\VideoId;
use App\Shared\Infrastructure\NotificationTypesUtils;
use App\Shared\Infrastructure\Request\BasicRequest;
use App\Shared\Infrastructure\Rules\CorrectMainTaskValue;
use App\Shared\Infrastructure\Utils\UserUtils;
use App\Task\Application\Command\SingleTask\Create\AlarmDto;
use App\Task\Application\Command\SingleTask\Create\TaskDto;
use App\Task\Application\Dto\BookmarksList;
use App\Task\Application\Dto\FilesList;
use App\Task\Application\Dto\NotesList;
use App\Task\Application\Dto\VideosList;
use App\User\Infrastructure\StringUtils;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class CreateRequest extends BasicRequest
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
            'task' => 'required|array',
            'task.name' => sprintf('required|string|filled|max:%s', Config::TASK_NAME_LENGTH),
            'task.content' => 'nullable|string',
            'task.date' => 'nullable|date',
            'task.mainTask' => ['nullable', 'string', new CorrectMainTaskValue()],
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
            'alarm.notifications.*.date' => 'date',
            'alarm.notifications.*.notificationTypes' => 'required|array|min:1',
            'alarm.notifications.*.notificationTypes.*' => [Rule::in($this->availableTypes)],
            'alarm.catalogs' => 'sometimes|present|array',
            'alarm.catalogs.*' => sprintf('integer|exists:catalogs,id,user_id,%s', $this->userId)
        ];
    }

    public function getTaskData(string $id): TaskDto
    {
        $mainTask = $this->task['mainTask'];

        return new TaskDto(
            new TaskId($id),
            $this->task['name'],
            StringUtils::trimContent($this->task['content']),
            $this->task['date'] !== null ? (new Carbon($this->task['date']))->toDateTimeImmutable() : null,
            $mainTask === null ? null : new TaskId($this->task['mainTask']),
            new CatalogsIdsList(...array_map(static fn(string $id) => new CatalogId($id), $this->task['catalogs'])),
            new NotesList(...array_map(static fn(string $id) => new NoteId($id), $this->task['notes'])),
            new FilesList(...array_map(static fn(string $id) => new FileId($id), $this->task['files'])),
            new VideosList(...array_map(static fn(string $id) => new VideoId($id), $this->task['videos'])),
            new BookmarksList(...array_map(static fn(string $id) => new BookmarkId($id), $this->task['bookmarks']))
        );
    }

    public function getAlarmDto(string $alarmId): ?AlarmDto
    {
        if ($this->alarm === null) {
            return null;
        }
        $alarm = new ImAlarmDto(
            new AlarmId($alarmId),
            $this->alarm['name'],
            StringUtils::trimContent($this->alarm['content']),
            new CatalogsIdsList(...array_map(static fn(string $id) => new CatalogId($id), $this->alarm['catalogs']))
        );

        $notifications = array_map(static fn(array $notification) => new Notification(
            Carbon::createFromFormat('Y-m-d H:i:s', $notification['date'])->toDateTimeImmutable(),
            new NotificationTypesList(
                ...array_map(static fn(int $id) => new NotificationTypeId($id), $notification['notificationTypes'])
            )
        ), $this->alarm['notifications']);

        return new AlarmDto($alarm, new Notifications(new AlarmId($alarmId), ...$notifications));
    }
}
