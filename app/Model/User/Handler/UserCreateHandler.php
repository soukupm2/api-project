<?php

declare(strict_types=1);

namespace App\Model\User\Handler;

use App\Core\Api\Auth\Authorizator;
use App\Model\User\Dto\UserCreateDto;
use App\Model\User\User;
use App\Model\User\UserRole;

final readonly class UserCreateHandler
{
    public function __construct(
        private UserCreateHandlerHelper $userCreateHandlerHelper,
        private Authorizator $authorizator,
    ) {
    }

    public function create(UserCreateDto $userCreateDto): User
    {
        $this->authorizator->requireRole([UserRole::ADMIN]);

        return $this->userCreateHandlerHelper->create($userCreateDto);
    }
}
