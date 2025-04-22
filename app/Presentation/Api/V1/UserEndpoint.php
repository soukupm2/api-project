<?php declare(strict_types=1);

namespace App\Presentation\Api\V1;

use App\Core\Api\Response\ApiResponseData;
use App\Presentation\Api\Endpoint;
use Nette\Application\Request;
use Nette\Http\IResponse;
use Ramsey\Uuid\Uuid;

class UserEndpoint extends Endpoint
{

    public function get(Request $request): ApiResponseData
    {
        \Tracy\Debugger::log($request->getParameter('id'));

        $data = [
            'id' => 123456789,
            'email' => 'test@test.com',
            'name' => 'Test User',
            'role' => 'admin',
        ];

        return new ApiResponseData($data);
    }


    public function post(): ApiResponseData
    {
        \Tracy\Debugger::log($this->httpRequest->getRawBody());

        return new ApiResponseData(['id' => 123456789], IResponse::S201_Created);
    }

    public function put(Request $request): ApiResponseData
    {
        \Tracy\Debugger::log($request->getParameter('id'));
        \Tracy\Debugger::log($this->httpRequest->getRawBody());

        return new ApiResponseData(null, IResponse::S204_NoContent);
    }

    public function delete(Request $request): ApiResponseData
    {
        \Tracy\Debugger::log($request->getParameter('id'));

        return new ApiResponseData(null, IResponse::S204_NoContent);
    }
}
