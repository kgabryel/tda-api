<?php

namespace App\Alarm\Infrastructure\Request\Single;

use App\Alarm\Application\Command\SingleAlarm\Create\Notification;
use App\Alarm\Domain\Entity\NotificationTypeId;
use App\Alarm\Domain\Entity\NotificationTypesList;
use App\Shared\Domain\Config;
use App\Shared\Infrastructure\NotificationTypesUtils;
use App\Shared\Infrastructure\Request\BasicRequest;
use App\Shared\Infrastructure\Utils\UserUtils;
use App\User\Infrastructure\StringUtils;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class CreateRequest extends BasicRequest
{
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
            'name' => sprintf('required|string|filled|max:%s', Config::ALARM_NAME_LENGTH),
            'content' => 'nullable|string',
            'notifications' => 'required|array|min:1',
            'notifications.*.date' => 'date',
            'notifications.*.notificationTypes' => 'required|array|min:1',
            'notifications.*.notificationTypes.*' => [Rule::in($this->availableTypes)],
            'catalogs' => 'present|array',
            'catalogs.*' => sprintf('integer|exists:catalogs,id,user_id,%s', $this->userId)
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getText(): ?string
    {
        return StringUtils::trimContent($this->get('content'));
    }

    public function getCatalogs(): array
    {
        return $this->catalogs;
    }

    public function getNotifications(): array
    {
        return array_map(
            static fn(array $notification) => new Notification(
                Carbon::createFromFormat('Y-m-d H:i:s', $notification['date'])->toDateTimeImmutable(),
                new NotificationTypesList(
                    ...array_map(static fn(int $id) => new NotificationTypeId($id), $notification['notificationTypes'])
                )
            ),
            $this->notifications
        );
    }
}
