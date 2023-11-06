<?php

namespace App\User\Domain\Entity;

enum SettingsKey: string
{
    case HIDE_DONE_TASKS = 'hide_done_tasks';
    case HIDE_REJECTED_TASKS = 'hide_rejected_tasks';
    case HIDE_DONE_SUBTASKS = 'hide_done_subtasks';
    case HIDE_INACTIVE_ALARMS = 'hide_inactive_alarms';
    case HIDE_INACTIVE_NOTIFICATIONS = 'hide_inactive_notifications';
    case HIDE_DONE_TASKS_IN_TASKS_GROUPS = 'hide_done_tasks_in_tasks_groups';
    case hide_inactive_alarms_in_alarms_groups = 'hide_inactive_alarms_in_alarms_groups';
    case AUTOCOMPLETE = 'autocomplete';
    case DEFAULT_PAGINATION = 'default_pagination';
    case NOTIFICATION_LANGUAGE = 'notification_language';
}
