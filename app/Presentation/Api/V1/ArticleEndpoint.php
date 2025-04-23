<?php

declare(strict_types=1);

namespace App\Presentation\Api\V1;

use App\Core\Api\Response\ApiResponseData;
use App\Model\Article\Dto\ArticleCreateDto;
use App\Model\Article\Dto\ArticleUpdateDto;
use App\Model\Article\Handler\ArticleCreateHandler;
use App\Model\Article\Handler\ArticleDeleteHandler;
use App\Model\Article\Handler\ArticleUpdateHandler;
use App\Model\Article\Query\ArticleByIdQuery;
use App\Presentation\Api\Endpoint;
use Nette\Application\Request;
use Nette\DI\Attributes\Inject;
use Nette\Http\IResponse;

final class ArticleEndpoint extends Endpoint
{
    #[Inject]
    public ArticleByIdQuery $articleByIdQuery;

    #[Inject]
    public ArticleCreateHandler $articleCreateHandler;

    #[Inject]
    public ArticleUpdateHandler $articleUpdateHandler;

    #[Inject]
    public ArticleDeleteHandler $articleDeleteHandler;

    public function get(Request $request): ApiResponseData
    {
        /** @var int|string $id Validated by schema */
        $id = $request->getParameter('id');

        $article = $this->articleByIdQuery->fetch((int) $id);

        return $article === null
            ? new ApiResponseData([
                'error' => 'Article not found',
            ], IResponse::S404_NotFound)
            : new ApiResponseData($article->toResponse());
    }

    public function post(): ApiResponseData
    {
        $articleCreateDto = ArticleCreateDto::fromRequestData((string) $this->httpRequest->getRawBody());

        $article = $this->articleCreateHandler->create($articleCreateDto);

        return new ApiResponseData([
            'id' => $article->id,
        ], IResponse::S201_Created);
    }

    public function put(Request $request): ApiResponseData
    {
        /** @var int|string $id Validated by schema */
        $id = $request->getParameter('id');

        $articleCreateDto = ArticleUpdateDto::fromRequestData((int) $id, (string) $this->httpRequest->getRawBody());

        $this->articleUpdateHandler->update($articleCreateDto);

        return new ApiResponseData(null, IResponse::S204_NoContent);
    }

    public function delete(Request $request): ApiResponseData
    {
        /** @var int|string $id Validated by schema */
        $id = $request->getParameter('id');

        $this->articleDeleteHandler->delete((int) $id);

        return new ApiResponseData(null, IResponse::S204_NoContent);
    }
}
