<?php declare(strict_types=1);

namespace App\Presentation\Api\Error;

use App\Core\Api\Response\ApiResponse;
use App\Core\Api\Response\ApiResponseData;
use Nette\Application\BadRequestException;
use Nette\Application\IPresenter;
use Nette\Application\Request;
use Nette\Application\Response;
use Tracy\Debugger;

class ErrorEndpoint implements IPresenter
{
    public function run(Request $request): Response
    {
        $exception = $request->getParameter('exception');

        if ($exception instanceof \Throwable) {
            Debugger::log($exception);
        }

        $statusCode = $exception instanceof BadRequestException ? $exception->getHttpCode() : 500;
        $message = $exception instanceof BadRequestException
            ? $exception->getMessage()
            : 'Error while processing request!';

        return new ApiResponse(new ApiResponseData(['message' => $message], $statusCode));
    }
}
