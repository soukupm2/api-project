<?php declare(strict_types=1);

namespace App\Presentation\Api\V1;

use App\Core\Api\Response\ApiResponseData;
use App\Presentation\Api\Endpoint;
use Nette\Application\Request;
use Nette\Http\IResponse;

class ArticleEndpoint extends Endpoint
{
    public function get(Request $request): ApiResponseData
    {
        \Tracy\Debugger::log($request->getParameter('id'));

        $data = [
            'id' => 123456789,
            'title' => 'test',
            'content' => 'test test',
            'author_id' => 123456789,
            'created_at' => (new \DateTimeImmutable())->format('c'),
            'updated_at' => (new \DateTimeImmutable())->format('c'),
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
