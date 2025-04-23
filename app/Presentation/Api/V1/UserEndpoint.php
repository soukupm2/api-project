<?php

declare(strict_types=1);

namespace App\Presentation\Api\V1;

use App\Core\Api\Response\ApiResponseData;
use App\Model\User\Dto\UserCreateDto;
use App\Model\User\Dto\UserUpdateDto;
use App\Model\User\Exception\UserAlreadyExistsException;
use App\Model\User\Exception\UserEmailAlreadyTakenException;
use App\Model\User\Handler\UserCreateHandler;
use App\Model\User\Handler\UserDeleteHandler;
use App\Model\User\Handler\UserUpdateHandler;
use App\Model\User\Query\UserByIdQuery;
use App\Presentation\Api\Endpoint;
use Nette\Application\Request;
use Nette\DI\Attributes\Inject;
use Nette\Http\IResponse;

final class UserEndpoint extends Endpoint
{
    #[Inject]
    public UserByIdQuery $userByIdQuery;

    #[Inject]
    public UserCreateHandler $userCreateHandler;

    #[Inject]
    public UserUpdateHandler $userUpdateHandler;

    #[Inject]
    public UserDeleteHandler $userDeleteHandler;

    public function get(Request $request): ApiResponseData
    {
        /** @var int|string $id Validated by schema */
        $id = $request->getParameter('id');

        $user = $this->userByIdQuery->fetch((int) $id);

        return new ApiResponseData($user->toResponse());
    }

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

    public function put(Request $request): ApiResponseData
    {
        /** @var int|string $id Validated by schema */
        $id = $request->getParameter('id');

        $useUpdateDto = UserUpdateDto::fromRequestData((int) $id, (string) $this->httpRequest->getRawBody());

        try {
            $this->userUpdateHandler->update($useUpdateDto);

            return new ApiResponseData(null, IResponse::S204_NoContent);
        } catch (UserEmailAlreadyTakenException $e) {
            return new ApiResponseData([
                'error' => $e->getMessage(),
            ], IResponse::S400_BadRequest);
        }
    }

    public function delete(Request $request): ApiResponseData
    {
        /** @var int|string $id Validated by schema */
        $id = $request->getParameter('id');

        $this->userDeleteHandler->delete((int) $id);

        return new ApiResponseData(null, IResponse::S204_NoContent);
    }
}
