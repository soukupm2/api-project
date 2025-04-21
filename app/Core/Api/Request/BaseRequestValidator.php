<?php

declare(strict_types=1);

namespace App\Core\Api\Request;

use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Nette\Http\Request;

class BaseRequestValidator implements RequestValidator
{
    public function __construct(
        private readonly string $schemaPath,
        private readonly ServerRequestFactory $serverRequestFactory,
    ) {
        if (! file_exists($this->schemaPath)) {
            throw new \RuntimeException('Schema path does not exist');
        }
    }

    public function validate(Request $request): void
    {
        $serverRequest = $this->serverRequestFactory->fromNetteHttpRequest($request);

        $validator = (new ValidatorBuilder())
            ->fromYamlFile($this->schemaPath)
            ->getServerRequestValidator();

        $validator->validate($serverRequest);
    }
}
