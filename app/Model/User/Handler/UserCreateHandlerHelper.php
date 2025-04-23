<?php

declare(strict_types=1);

namespace App\Model\User\Handler;

use App\Model\User\Dto\UserCreateDto;
use App\Model\User\Exception\UserAlreadyExistsException;
use App\Model\User\User;
use App\Model\User\UserRepository;
use Nette\Security\Passwords;

final readonly class UserCreateHandlerHelper
{
    public function __construct(
        private UserRepository $userRepository,
        private Passwords $passwords,
    ) {
    }

    public function create(UserCreateDto $userCreateDto): User
    {
        $existingUser = $this->userRepository->findByEmail($userCreateDto->email);

        if ($existingUser) {
            throw new UserAlreadyExistsException("User with email '{$userCreateDto->email}' already exists");
        }

        return $this->userRepository->insert([
            UserRepository::COL_EMAIL => $userCreateDto->email,
            UserRepository::COL_NAME => $userCreateDto->name,
            UserRepository::COL_ROLE => $userCreateDto->role->value,
            UserRepository::COL_PASSWORD_HASH => $this->passwords->hash($userCreateDto->password),
        ]);
    }
}
