<?php

declare(strict_types=1);

namespace App\Model\User\Handler;

use App\Core\Api\Auth\Authorizator;
use App\Model\Exception\EntityNotFoundException;
use App\Model\User\UserRepository;
use App\Model\User\UserRole;

final readonly class UserDeleteHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private Authorizator $authorizator,
    ) {
    }

    public function delete(int $id): void
    {
        $this->authorizator->requireRole([UserRole::ADMIN]);

        $user = $this->userRepository->findById($id);

        if (! $user) {
            throw new EntityNotFoundException('User not found');
        }

        $this->userRepository->delete($id);
    }
}
