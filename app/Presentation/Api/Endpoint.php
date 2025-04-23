<?php

declare(strict_types=1);

namespace App\Presentation\Api;

use App\Core\Api\Exception\Request\InvalidHttpMethodRequestException;
use App\Core\Api\Request\RequestValidator;
use App\Core\Api\Response\ApiResponse;
use App\Core\Api\Response\ApiResponseData;
use App\Core\Api\Response\ResponseValidator;
use App\Model\Exception\EntityNotFoundException;
use App\Model\User\Exception\AuthorizationException;
use Nette\Application\IPresenter;
use Nette\Application\Request;
use Nette\Application\Response;
use Nette\DI\Attributes\Inject;
use Nette\Http\IResponse;
use Tracy\Debugger;
use Tracy\ILogger;

abstract class Endpoint implements IPresenter
{
    private const METHODS = ['head', 'get', 'post', 'put', 'patch', 'delete', 'options'];

    #[Inject]
    public \Nette\Http\Request $httpRequest;

    #[Inject]
    public \Nette\Http\Response $httpResponse;

    #[Inject]
    public RequestValidator $requestValidator;

    #[Inject]
    public ResponseValidator $responseValidator;

    public function run(Request $request): Response
    {
        try {
            $this->requestValidator->validate($this->httpRequest);

            $handler = $this->findHandler($request);

            $methodName = $handler->getName();

            /** @var ApiResponseData $response */
            $response = $this->{$methodName}($request);

            $this->setupHttpResponse($response);

            $this->responseValidator->validate($this->httpRequest, $this->httpResponse, $response);

            return new ApiResponse($response);
        } catch (EntityNotFoundException $exception) {
            $response = new ApiResponseData(
                [
                    'error' => $exception->getMessage(),
                ],
                IResponse::S404_NotFound
            );

            $this->setupHttpResponse($response);

            $this->responseValidator->validate($this->httpRequest, $this->httpResponse, $response);

            return new ApiResponse($response);
        } catch (AuthorizationException $exception) {
            $response = new ApiResponseData(
                [
                    'error' => $exception->getMessage(),
                ],
                IResponse::S403_Forbidden
            );

            $this->setupHttpResponse($response);

            $this->responseValidator->validate($this->httpRequest, $this->httpResponse, $response);

            return new ApiResponse($response);
        } catch (\Throwable $exception) {
            Debugger::log($exception, ILogger::EXCEPTION);

            $response = new ApiResponseData(
                [
                    'error' => 'An error occurred while processing request',
                ],
                IResponse::S500_InternalServerError
            );

            $this->setupHttpResponse($response);

            return new ApiResponse($response);
        }
    }

    private function setupHttpResponse(ApiResponseData $response): void
    {
        $this->httpResponse->setCode($response->statusCode);
        $this->httpResponse->setContentType($response->contentType, 'utf-8');

        foreach ($response->headers as $header => $value) {
            $this->httpResponse->setHeader($header, $value);
        }
    }

    private function findHandler(Request $request): \ReflectionMethod
    {
        $name = $request->getMethod();

        if ($name === null || ! in_array(strtolower($name), self::METHODS, true)) {
            throw new InvalidHttpMethodRequestException($request);
        }

        if (! method_exists($this, $name)) {
            throw new InvalidHttpMethodRequestException($request);
        }

        return new \ReflectionMethod($this, $name);
    }
}
