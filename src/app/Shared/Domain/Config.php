<?php

namespace App\Shared\Domain;

abstract class Config
{
    public const NOTE_NAME_LENGTH = 100;
    public const VIDEO_NAME_LENGTH = 100;
    public const COLOR_NAME_LENGTH = 50;
    public const CATALOG_NAME_LENGTH = 100;
    public const BOOKMARK_NAME_LENGTH = 50;
    public const EMAIL_LENGTH = 255;
    public const PASSWORD_LENGTH = 255;
    public const TASK_NAME_LENGTH = 100;
    public const ALARM_NAME_LENGTH = 100;
    public const FILE_NAME_LENGTH = 50;
    public const MAX_FILE_SIZE = 5242880;
    public const PERIODIC_NOTIFICATION_MAX_VALUE = 31536000;
    public const MAX_INTERVAL_NAME_LENGTH = 20;
}
