<?php

declare(strict_types=1);

namespace App\Model\User;

enum UserRole: string
{
    case ADMIN = 'admin';
    case AUTHOR = 'author';
    case READER = 'reader';
}
