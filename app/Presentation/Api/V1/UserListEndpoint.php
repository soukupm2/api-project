<?php

declare(strict_types=1);

namespace App\Presentation\Api\V1;

use App\Core\Api\Response\ApiResponseData;
use App\Presentation\Api\Endpoint;
use Ramsey\Uuid\Uuid;

class UserListEndpoint extends Endpoint
{
    public function get(): ApiResponseData
    {
        return new ApiResponseData(
            payload: [
                [
                    'id' => 123456789,
                    'email' => 'test@test.com',
                    'name' => 'Test User',
                    'role' => 'admin',
                ],
            ],
        );
    }
}
