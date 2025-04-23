<?php

declare(strict_types=1);

namespace App\Model\User\Query;

use App\Core\Api\Auth\Authorizator;
use App\Model\User\User;
use App\Model\User\UserRepository;
use App\Model\User\UserRole;

final readonly class UserListQuery
{
    public function __construct(
        private UserRepository $userRepository,
        private Authorizator $authorizator,
    ) {
    }

    /**
     * @return User[]
     */
    public function fetch(): array
    {
        $this->authorizator->requireRole([UserRole::ADMIN]);

        return $this->userRepository->findAll();
    }
}
