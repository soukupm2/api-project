<?php

declare(strict_types=1);

namespace App\Model;

interface UserOwnedEntity
{
    public function getOwnerId(): int;
}
