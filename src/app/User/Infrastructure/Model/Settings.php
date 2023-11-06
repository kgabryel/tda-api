<?php

namespace App\User\Infrastructure\Model;

use App\Shared\Domain\Entity\UserId;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    public $timestamps = false;

    protected $table = 'settings';

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(UserId $userId): self
    {
        $this->user_id = $userId->getValue();

        return $this;
    }

    public function setNotificationLanguage(string $lang): self
    {
        $this->notification_lang = $lang;

        return $this;
    }

    public function getNotificationLanguage(): string
    {
        return $this->notification_lang;
    }

    public function setDefaultPagination(int $defaultPagination): self
    {
        $this->default_pagination = $defaultPagination;

        return $this;
    }

    public function getDefaultPagination(): string
    {
        return (string)$this->default_pagination;
    }

    public function setHideDoneTasks(bool $hideDoneTasks): self
    {
        $this->hide_done_tasks = $hideDoneTasks;

        return $this;
    }

    public function getHideDoneTasks(): bool
    {
        return $this->hide_done_tasks;
    }

    public function setHideRejectedTasks(bool $hideRejectedTasks): self
    {
        $this->hide_rejected_tasks = $hideRejectedTasks;

        return $this;
    }

    public function getHideRejectedTasks(): bool
    {
        return $this->hide_rejected_tasks;
    }

    public function setHideDoneSubtasks(bool $hideDoneSubtasks): self
    {
        $this->hide_done_subtasks = $hideDoneSubtasks;

        return $this;
    }

    public function getHideDoneSubtasks(): bool
    {
        return $this->hide_done_subtasks;
    }

    public function setHideInactiveAlarms(bool $hideInactiveAlarms): self
    {
        $this->hide_inactive_alarms = $hideInactiveAlarms;

        return $this;
    }

    public function getHideInactiveAlarms(): bool
    {
        return $this->hide_inactive_alarms;
    }

    public function setHideInactiveNotifications(bool $hideInactiveNotifications): self
    {
        $this->hide_inactive_notifications = $hideInactiveNotifications;

        return $this;
    }

    public function getHideInactiveNotifications(): bool
    {
        return $this->hide_inactive_notifications;
    }

    public function setHideDoneTasksInTasksGroups(bool $hideDoneTasksInTasksGroups): self
    {
        $this->hide_done_tasks_in_tasks_groups = $hideDoneTasksInTasksGroups;

        return $this;
    }

    public function getHideDoneTasksInTasksGroups(): bool
    {
        return $this->hide_done_tasks_in_tasks_groups;
    }

    public function setHideInactiveAlarmsInAlarmsGroups(bool $hideInactiveAlarmsInAlarmsGroups): self
    {
        $this->hide_inactive_alarms_in_alarms_groups = $hideInactiveAlarmsInAlarmsGroups;

        return $this;
    }

    public function getHideInactiveAlarmsInAlarmsGroups(): bool
    {
        return $this->hide_inactive_alarms_in_alarms_groups;
    }

    public function setAutocomplete(bool $autocomplete): self
    {
        $this->autocomplete = $autocomplete;

        return $this;
    }

    public function getAutocomplete(): bool
    {
        return $this->autocomplete;
    }
}
