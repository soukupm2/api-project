<?php declare(strict_types=1);

namespace App\Presentation\Api\V1;

use App\Core\Api\Response\ApiResponseData;
use App\Presentation\Api\Endpoint;
use Nette\Application\Request;
use Ramsey\Uuid\Uuid;

class AuthLoginEndpoint extends Endpoint
{
    public function post(Request $request): ApiResponseData
    {
        \Tracy\Debugger::log($this->httpRequest->getRawBody());

        return new ApiResponseData(['token' => Uuid::uuid4()->toString()]);
    }
}
