<?php

declare(strict_types=1);

namespace App\Model\User\Dto;

use Nette\Utils\Json;

final readonly class UserLoginDto
{
    public function __construct(
        public string $email,
        public string $password,
    ) {
    }

    public static function fromRequestData(string $body): self
    {
        $data = Json::decode($body, true);

        // @phpstan-ignore-next-line
        return new self(email: $data['email'], password: $data['password']);
    }
}
