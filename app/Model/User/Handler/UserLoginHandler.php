<?php

declare(strict_types=1);

namespace App\Model\User\Handler;

use App\Core\Api\Auth\JwtHandler;
use App\Model\User\Dto\UserLoginDto;
use App\Model\User\Exception\InvalidCredentialsException;
use App\Model\User\UserRepository;
use Nette\Security\Passwords;

final readonly class UserLoginHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private Passwords $passwords,
        private JwtHandler $jwtHandler,
    ) {
    }

    public function login(UserLoginDto $userLoginDto): string
    {
        $user = $this->userRepository->findByEmail($userLoginDto->email);

        if (! $user || ! $this->passwords->verify($userLoginDto->password, $user->passwordHash)) {
            throw new InvalidCredentialsException('Invalid credentials');
        }

        return $this->jwtHandler->encode($user->id);
    }
}
