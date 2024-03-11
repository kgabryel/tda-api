<?php

namespace App\Alarm\Infrastructure\Command\SendNotifications;

use App\Alarm\Domain\Entity\NotificationTypeValue;
use App\Shared\Domain\Entity\UserId;
use Illuminate\Database\Eloquent\Collection;

class NotificationToSend
{
    private UserId $userId;
    private bool $emailAvailable;
    private ?string $notificationEmail;
    private int $notificationId;
    private string $name;
    private ?string $content;
    private string $date;
    private ?string $deactivationCode;
    private string $alarmId;
    private ?string $taskId;
    private ?string $groupId;
    private string $lang;
    private array $notifications;
    private array $types;

    public function __construct(
        UserId $userId,
        bool $emailAvailable,
        ?string $notificationEmail,
        int $notificationId,
        string $name,
        ?string $content,
        string $date,
        ?string $deactivationCode,
        string $alarmId,
        ?string $taskId,
        ?string $groupId,
        string $lang,
        array $notifications,
        NotificationTypeValue ...$types
    ) {
        $this->userId = $userId;
        $this->emailAvailable = $emailAvailable;
        $this->notificationEmail = $notificationEmail;
        $this->notificationId = $notificationId;
        $this->name = $name;
        $this->content = $content;
        $this->date = $date;
        $this->deactivationCode = $deactivationCode;
        $this->alarmId = $alarmId;
        $this->taskId = $taskId;
        $this->groupId = $groupId;
        $this->lang = $lang;
        $this->notifications = $notifications;
        $this->types = $types;
    }

    public static function create(object $notificationBuff): self
    {
        /** @var Collection $notifications */
        $notifications = $notificationBuff->notification->alarm->notifications;
        /** @var Collection $notifications */
        $types = $notificationBuff->notification->notificationTypes->pluck('name')
            ->map(static fn(string $name) => NotificationTypeValue::from($name))
            ->toArray();

        return new self(
            new UserId($notificationBuff->userId),
            $notificationBuff->email_available,
            $notificationBuff->notification_email,
            $notificationBuff->notification_id,
            $notificationBuff->name,
            $notificationBuff->content,
            $notificationBuff->time->format('Y-m-d H:i:s'),
            $notifications->isEmpty() ? null : $notificationBuff->deactivation_code,
            $notificationBuff->alarmId,
            $notificationBuff->taskId,
            $notificationBuff->groupId,
            $notificationBuff->notification_lang,
            $notificationBuff->notification->alarm->notifications->toArray(),
            ...$types
        );
    }

    public function getId(): int
    {
        return $this->notificationId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getDeactivationCode(): ?string
    {
        return $this->deactivationCode;
    }

    public function getAlarmId(): string
    {
        return $this->alarmId;
    }

    public function getTaskId(): ?string
    {
        return $this->taskId;
    }

    public function getGroupId(): ?string
    {
        return $this->groupId;
    }

    public function getLang(): string
    {
        return $this->lang;
    }

    public function getNotifications(): array
    {
        return $this->notifications;
    }

    public function getTypes(): array
    {
        return $this->types;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function isEmailAvailable(): bool
    {
        return $this->emailAvailable;
    }

    public function getNotificationEmail(): ?string
    {
        if (!$this->emailAvailable) {
            return null;
        }

        return $this->notificationEmail;
    }
}
