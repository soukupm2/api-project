<?php

declare(strict_types=1);

namespace App\Model\User\Handler;

use App\Core\Api\Auth\Authorizator;
use App\Model\Exception\EntityNotFoundException;
use App\Model\User\Dto\UserUpdateDto;
use App\Model\User\Exception\UserEmailAlreadyTakenException;
use App\Model\User\UserRepository;
use App\Model\User\UserRole;
use Nette\Database\UniqueConstraintViolationException;

final readonly class UserUpdateHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private Authorizator $authorizator,
    ) {
    }

    public function update(UserUpdateDto $userUpdateDto): void
    {
        $this->authorizator->requireRole([UserRole::ADMIN]);

        $user = $this->userRepository->findById($userUpdateDto->id);

        if (! $user) {
            throw new EntityNotFoundException('User not found');
        }

        try {
            $this->userRepository->update($user->id, [
                UserRepository::COL_EMAIL => $userUpdateDto->email,
                UserRepository::COL_NAME => $userUpdateDto->name,
                UserRepository::COL_ROLE => $userUpdateDto->role->value,
            ]);
        } catch (UniqueConstraintViolationException) {
            throw new UserEmailAlreadyTakenException("Email '{$userUpdateDto->email}' already taken");
        }
    }
}
