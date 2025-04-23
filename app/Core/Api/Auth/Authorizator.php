<?php

declare(strict_types=1);

namespace App\Core\Api\Auth;

use App\Model\User\Exception\AuthorizationException;
use App\Model\User\User;
use App\Model\User\UserRepository;
use App\Model\User\UserRole;
use App\Model\UserOwnedEntity;
use Nette\Http\Request;
use Tracy\ILogger;

final readonly class Authorizator
{
    public function __construct(
        private Request $httpRequest,
        private JwtHandler $jwtHandler,
        private UserRepository $userRepository,
    ) {
    }

    public function getLoggedInUser(): ?User
    {
        $headerData = $this->parseHeader();

        /** @var int|null $userId */
        $userId = $headerData['id'] ?? null;

        return $userId !== null ? $this->userRepository->findById($userId) : null;
    }

    public function requireLoggedInUser(): User
    {
        return $this->getLoggedInUser() ?? throw new AuthorizationException('User not logged in!');
    }

    /**
     * @param UserRole[] $role
     */
    public function requireRole(array $role): User
    {
        $user = $this->requireLoggedInUser();

        if (! in_array($user->role, $role, true)) {
            throw new AuthorizationException('User not allowed!');
        }

        return $user;
    }

    public function requireOwnerOrRole(UserOwnedEntity $userOwnedEntity, ?UserRole $role = null): User
    {
        $user = $this->requireLoggedInUser();

        if (! (($role && $user->role === $role) || $user->id === $userOwnedEntity->getOwnerId())) {
            throw new AuthorizationException('User not allowed!');
        }

        return $user;
    }

    /**
     * @return array<string, int>|null
     */
    private function parseHeader(): ?array
    {
        $header = $this->httpRequest->getHeader('Authorization');

        if (! $header) {
            return null;
        }

        try {
            $token = str_replace('Bearer ', '', $header);

            return $this->jwtHandler->decode($token);
        } catch (\Throwable $throwable) {
            \Tracy\Debugger::log($throwable, ILogger::EXCEPTION);

            return null;
        }
    }
}
