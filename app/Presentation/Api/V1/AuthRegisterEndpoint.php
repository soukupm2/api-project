<?php

declare(strict_types=1);

namespace App\Presentation\Api\V1;

use App\Core\Api\Response\ApiResponseData;
use App\Model\User\Dto\UserCreateDto;
use App\Model\User\Exception\UserAlreadyExistsException;
use App\Model\User\Handler\UserRegisterHandler;
use App\Presentation\Api\Endpoint;
use Nette\DI\Attributes\Inject;
use Nette\Http\IResponse;

final class AuthRegisterEndpoint extends Endpoint
{
    #[Inject]
    public UserRegisterHandler $userCreateHandler;

    public function post(): ApiResponseData
    {
        $userCreateDto = UserCreateDto::fromRequestData((string) $this->httpRequest->getRawBody());

        try {
            $user = $this->userCreateHandler->create($userCreateDto);

            return new ApiResponseData([
                'id' => $user->id,
            ], IResponse::S201_Created);
        } catch (UserAlreadyExistsException $e) {
            return new ApiResponseData([
                'error' => $e->getMessage(),
            ], IResponse::S409_Conflict);
        }
    }
}
