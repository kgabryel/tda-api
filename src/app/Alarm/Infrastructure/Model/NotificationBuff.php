<?php

namespace App\Alarm\Infrastructure\Model;

use App\Alarm\Domain\Entity\NotificationId;
use DateTime;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationBuff extends Model
{
    public $timestamps = false;

    protected $table = 'notifications_buff';

    protected $casts = [
        'time' => 'datetime'
    ];

    public function setTime(DateTimeImmutable $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function setNotificationId(NotificationId $notificationId): self
    {
        $this->notification_id = $notificationId->getValue();

        return $this;
    }

    public function getDate(): DateTime
    {
        return $this->time;
    }

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class, 'notification_id', 'id');
    }
}
