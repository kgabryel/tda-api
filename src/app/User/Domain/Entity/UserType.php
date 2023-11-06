<?php

namespace App\User\Domain\Entity;

enum UserType
{
    case NORMAL_ACCOUNT;
    case FB_ACCOUNT;
}
