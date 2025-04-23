<?php

declare(strict_types=1);

namespace App\Presentation\Api\V1;

use App\Core\Api\Response\ApiResponseData;
use App\Model\User\Query\UserListQuery;
use App\Model\User\User;
use App\Presentation\Api\Endpoint;
use Nette\DI\Attributes\Inject;

final class UserListEndpoint extends Endpoint
{
    #[Inject]
    public UserListQuery $userListQuery;

    public function get(): ApiResponseData
    {
        $users = $this->userListQuery->fetch();

        return new ApiResponseData(
            payload: array_map(static fn (User $u) => $u->toResponse(), $users),
        );
    }
}
