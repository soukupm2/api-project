<?php

declare(strict_types=1);

namespace App\Core\Api\Response;

use Nette;
use Nette\Utils\Json;

final readonly class ApiResponse implements Nette\Application\Response
{
    public function __construct(
        public ApiResponseData $apiResponseData,
    ) {
    }

    public function send(Nette\Http\IRequest $httpRequest, Nette\Http\IResponse $httpResponse): void
    {
        $httpResponse->setContentType($this->apiResponseData->contentType, 'utf-8');
        $httpResponse->setCode($this->apiResponseData->statusCode);

        foreach ($this->apiResponseData->headers as $header => $value) {
            $httpResponse->setHeader($header, $value);
        }

        if ($this->apiResponseData->payload !== null) {
            echo Json::encode($this->apiResponseData->payload);
        }
    }
}
