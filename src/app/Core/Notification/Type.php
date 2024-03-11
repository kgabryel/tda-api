<?php

namespace App\Core\Notification;

enum Type: string
{
    case CATALOGS = 'catalogs';
    case TASKS = 'tasks';
    case ALARMS = 'alarms';
    case NOTES = 'notes';
    case FILES = 'files';
    case VIDEOS = 'videos';
    case BOOKMARKS = 'bookmarks';
}
