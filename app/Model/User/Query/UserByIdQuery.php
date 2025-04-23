<?php

declare(strict_types=1);

namespace App\Model\User\Query;

use App\Core\Api\Auth\Authorizator;
use App\Model\Exception\EntityNotFoundException;
use App\Model\User\User;
use App\Model\User\UserRepository;
use App\Model\User\UserRole;

final readonly class UserByIdQuery
{
    public function __construct(
        private UserRepository $userRepository,
        private Authorizator $authorizator,
    ) {
    }

    public function fetch(int $id): User
    {
        $this->authorizator->requireRole([UserRole::ADMIN]);

        return $this->userRepository->findById($id) ?? throw new EntityNotFoundException('User not found.');
    }
}
