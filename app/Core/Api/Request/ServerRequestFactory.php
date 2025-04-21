<?php

declare(strict_types=1);

namespace App\Core\Api\Request;

use GuzzleHttp\Psr7\ServerRequest;
use Nette\Http\Request;
use Psr\Http\Message\ServerRequestInterface;

class ServerRequestFactory
{
    public function fromNetteHttpRequest(Request $request): ServerRequestInterface
    {
        /** @var array<string, string[]|string> $headers */
        $headers = $request->getHeaders();

        return new ServerRequest(
            method: $request->getMethod(),
            uri: (string) $request->getUrl(),
            headers: $headers,
            body: $request->getRawBody(),
            version: '1.1',
        );
    }
}
