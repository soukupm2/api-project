<?php

declare(strict_types=1);

namespace App\Core\Api\Response;

use Nette\Http\Response;
use Nette\Utils\Json;
use Psr\Http\Message\ResponseInterface;

class ServerResponseFactory
{
    public function fromNetteResponse(Response $response, ApiResponseData $apiResponse): ResponseInterface
    {
        $body = $apiResponse->payload !== null ? Json::encode($apiResponse->payload) : null;
        /** @var array<string, string[]|string> $headers */
        $headers = $response->getHeaders();

        return new \GuzzleHttp\Psr7\Response(status: $apiResponse->statusCode, headers: $headers, body: $body);
    }
}
