<?php

declare(strict_types=1);

namespace App\Core\Api\Exception\Request;

use Nette\Application\Request;

abstract class BadRequestException extends \RuntimeException
{
    private Request $request;

    public function __construct(Request $request, string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->request = clone $request;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
