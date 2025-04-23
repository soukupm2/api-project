<?php

declare(strict_types=1);

namespace App\Core\Api\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final readonly class JwtHandler
{
    public function __construct(
        private string $publicKey,
        private string $privateKey,
    ) {
    }

    public function encode(int $userId): string
    {
        $payload = [
            'id' => $userId,
        ];

        return JWT::encode($payload, $this->privateKey, 'EdDSA');
    }

    /**
     * @return array<string, int>
     */
    public function decode(string $jwt): array
    {
        /** @var array<string, int> $result */
        $result = (array) JWT::decode($jwt, new Key($this->publicKey, 'EdDSA'));

        return $result;
    }
}
