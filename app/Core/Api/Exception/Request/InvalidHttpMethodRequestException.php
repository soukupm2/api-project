<?php

declare(strict_types=1);

namespace App\Core\Api\Exception\Request;

use Nette\Application\Request;
use Throwable;

class InvalidHttpMethodRequestException extends BadRequestException
{
    public function __construct(Request $request, ?Throwable $previous = null)
    {
        $method = $request->getMethod();
        $message = "Unsupported HTTP method '{$method}'";
        $code = 405;

        parent::__construct($request, $message, $code, $previous);
    }
}
