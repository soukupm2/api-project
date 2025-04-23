<?php

declare(strict_types=1);

namespace App\Model\User\Dto;

use App\Model\User\UserRole;
use Nette\Utils\Json;

final readonly class UserUpdateDto
{
    public function __construct(
        public int $id,
        public string $email,
        public string $name,
        public UserRole $role,
    ) {
    }

    public static function fromRequestData(int $userId, string $body): self
    {
        $data = Json::decode($body, true);

        return new self(
            id: $userId,
            // @phpstan-ignore-next-line
            email: $data['email'],
            // @phpstan-ignore-next-line
            name: $data['name'],
            // @phpstan-ignore-next-line
            role: UserRole::from($data['role'])
        );
    }
}
