<?php

declare(strict_types=1);

namespace App\Presentation\Api;

use App\Core\Api\Exception\Request\Method\InvalidHttpMethodRequestException;
use App\Core\Api\Request\RequestValidator;
use App\Core\Api\Response\ApiResponse;
use App\Core\Api\Response\ApiResponseData;
use App\Core\Api\Response\ResponseValidator;
use Nette\Application\IPresenter;
use Nette\Application\Request;
use Nette\Application\Response;
use Nette\Application\Responses\JsonResponse;
use Nette\DI\Attributes\Inject;
use Tracy\Debugger;
use Tracy\ILogger;

class Endpoint implements IPresenter
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
            $response = $this->{$methodName}();

            $this->httpResponse->setCode($response->statusCode);
            $this->httpResponse->setContentType($response->contentType, 'utf-8');

            foreach ($response->headers as $header => $value) {
                $this->httpResponse->setHeader($header, $value);
            }

            $this->responseValidator->validate($this->httpRequest, $this->httpResponse, $response);

            return new ApiResponse($response);
        } catch (\Throwable $exception) {
            $this->httpResponse->setCode(500);

            Debugger::log($exception, ILogger::EXCEPTION);

            return new JsonResponse([
                'error' => 'An error occurred while processing request',
            ]);
        }
    }

    private function findHandler(Request $request): \ReflectionMethod
    {
        $name = $request->getMethod();

        if ($name === null || ! in_array(strtolower($name), self::METHODS, true)) {
            $this->setAvailableHeaders();

            throw new InvalidHttpMethodRequestException($request);
        }

        if (! method_exists($this, $name)) {
            $this->setAvailableHeaders();

            throw new InvalidHttpMethodRequestException($request);
        }

        return new \ReflectionMethod($this, $name);
    }

    /**
     * @return string[]
     */
    private function getAvailableHandlers(): array
    {
        $reflectionClass = new \ReflectionClass($this);

        $availableHandlers = [];

        foreach ($reflectionClass->getMethods() as $reflectionMethod) {
            if (in_array(strtolower($reflectionMethod->getName()), self::METHODS, true)) {
                $availableHandlers[] = strtoupper($reflectionMethod->getName());
            }
        }

        return $availableHandlers;
    }

    private function setAvailableHeaders(): void
    {
        $this->httpResponse->setHeader(
            'Access-Control-Allow-Methods',
            implode(', ', $this->getAvailableHandlers())
        );
    }
}
