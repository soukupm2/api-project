<?php

declare(strict_types=1);

namespace App\Model\User\Dto;

use App\Model\User\UserRole;
use Nette\Utils\Json;

final readonly class UserCreateDto
{
    public function __construct(
        public string $email,
        public string $name,
        public string $password,
        public UserRole $role,
    ) {
    }

    public static function fromRequestData(string $body): self
    {
        $data = Json::decode($body, true);

        return new self(
            // @phpstan-ignore-next-line
            email: $data['email'],
            // @phpstan-ignore-next-line
            name: $data['name'],
            // @phpstan-ignore-next-line
            password: $data['password'],
            // @phpstan-ignore-next-line
            role: UserRole::from($data['role'])
        );
    }
}
