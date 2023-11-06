<?php

namespace App\Alarm\Domain\Entity;

enum NotificationTypeValue: string
{
    case EMAIL = 'E-mail';
    case WEB = 'Web';
    case PUSH = 'Push';
}
