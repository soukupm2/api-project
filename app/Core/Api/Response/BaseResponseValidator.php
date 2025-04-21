<?php

declare(strict_types=1);

namespace App\Core\Api\Response;

use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Nette\Http\Request;
use Nette\Http\Response;

final readonly class BaseResponseValidator implements ResponseValidator
{
    public function __construct(
        private string $schemaPath,
        private ServerResponseFactory $serverResponseFactory,
    ) {
        if (! file_exists($this->schemaPath)) {
            throw new \RuntimeException('Schema path does not exist');
        }
    }

    public function validate(Request $request, Response $httpResponse, ApiResponseData $apiResponse): void
    {
        $serverResponse = $this->serverResponseFactory->fromNetteResponse($httpResponse, $apiResponse);

        $validator = (new ValidatorBuilder())
            ->fromYamlFile($this->schemaPath)
            ->getResponseValidator();

        $operation = new OperationAddress($request->getUrl()->getPath(), strtolower($request->getMethod()));

        $validator->validate($operation, $serverResponse);
    }
}
