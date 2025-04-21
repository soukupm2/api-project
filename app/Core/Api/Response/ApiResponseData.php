<?php

declare(strict_types=1);

namespace App\Core\Api\Response;

use Nette;

final readonly class ApiResponseData
{
    /**
     * @param array<array-key, mixed>|null $payload
     * @param array<string, string> $headers
     */
    public function __construct(
        public ?array $payload,
        public int $statusCode = Nette\Http\IResponse::S200_OK,
        public string $contentType = 'application/json',
        public array $headers = [],
    ) {
    }
}
