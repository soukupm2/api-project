<?php declare(strict_types=1);

namespace App\Presentation\Api\V1;

use App\Core\Api\Response\ApiResponseData;
use App\Presentation\Api\Endpoint;
use Ramsey\Uuid\Uuid;

class ArticleListEndpoint extends Endpoint
{
    public function get(): ApiResponseData
    {
        $data = [
            'id' => 123456789,
            'title' => 'test',
            'content' => 'test test',
            'author_id' => 123456789,
            'created_at' => (new \DateTimeImmutable())->format('c'),
            'updated_at' => (new \DateTimeImmutable())->format('c'),
        ];

        return new ApiResponseData([$data]);
    }
}
