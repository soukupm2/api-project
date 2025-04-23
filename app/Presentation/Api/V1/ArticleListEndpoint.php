<?php

declare(strict_types=1);

namespace App\Presentation\Api\V1;

use App\Core\Api\Response\ApiResponseData;
use App\Model\Article\Article;
use App\Model\Article\Query\ArticleListQuery;
use App\Presentation\Api\Endpoint;
use Nette\DI\Attributes\Inject;

final class ArticleListEndpoint extends Endpoint
{
    #[Inject]
    public ArticleListQuery $articleListQuery;

    public function get(): ApiResponseData
    {
        \Tracy\Debugger::log(T_FINAL);

        $articles = $this->articleListQuery->fetch();

        return new ApiResponseData(array_map(static fn (Article $a) => $a->toResponse(), array_values($articles)));
    }
}
