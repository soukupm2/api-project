<?php

declare(strict_types=1);

namespace App\Core\Api\Response;

use Nette\Http\Request;
use Nette\Http\Response;

interface ResponseValidator
{
    public function validate(Request $request, Response $httpResponse, ApiResponseData $apiResponse): void;
}
