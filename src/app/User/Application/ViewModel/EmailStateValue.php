<?php

namespace App\User\Application\ViewModel;

enum EmailStateValue: int
{
    case EMAIL_CONFIRMED = 1;
    case EMAIL_UNCONFIRMED = 2;
    case EMAIL_EMPTY = 3;
}
