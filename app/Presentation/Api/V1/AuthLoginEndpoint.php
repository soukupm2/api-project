<?php

declare(strict_types=1);

namespace App\Presentation\Api\V1;

use App\Core\Api\Response\ApiResponseData;
use App\Model\User\Dto\UserLoginDto;
use App\Model\User\Exception\InvalidCredentialsException;
use App\Model\User\Handler\UserLoginHandler;
use App\Presentation\Api\Endpoint;
use Nette\DI\Attributes\Inject;
use Nette\Http\IResponse;

final class AuthLoginEndpoint extends Endpoint
{
    #[Inject]
    public UserLoginHandler $userLoginHandler;

    public function post(): ApiResponseData
    {
        $loginDto = UserLoginDto::fromRequestData((string) $this->httpRequest->getRawBody());

        try {
            $token = $this->userLoginHandler->login($loginDto);

            return new ApiResponseData([
                'token' => $token,
            ]);
        } catch (InvalidCredentialsException $e) {
            return new ApiResponseData([
                'error' => $e->getMessage(),
            ], IResponse::S401_Unauthorized);
        }
    }
}
