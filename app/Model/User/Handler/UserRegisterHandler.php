<?php

declare(strict_types=1);

namespace App\Model\User\Handler;

use App\Model\User\Dto\UserCreateDto;
use App\Model\User\User;

final readonly class UserRegisterHandler
{
    public function __construct(
        private UserCreateHandlerHelper $userCreateHandlerHelper
    ) {
    }

    public function create(UserCreateDto $userCreateDto): User
    {
        return $this->userCreateHandlerHelper->create($userCreateDto);
    }
}
