<?php

namespace App\Enums;

enum UserProfileActionTypeEnum: string
{
    case LIKE = 'like';
    case DISLIKE = 'dislike';
    case NO = 'no';
}
