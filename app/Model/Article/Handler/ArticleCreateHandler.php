<?php

declare(strict_types=1);

namespace App\Model\Article\Handler;

use App\Core\Api\Auth\Authorizator;
use App\Model\Article\Article;
use App\Model\Article\ArticleRepository;
use App\Model\Article\Dto\ArticleCreateDto;
use App\Model\User\UserRole;

final readonly class ArticleCreateHandler
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private Authorizator $authorizator,
    ) {
    }

    public function create(ArticleCreateDto $articleDto): Article
    {
        $loggedInUser = $this->authorizator->requireRole([UserRole::AUTHOR, UserRole::ADMIN]);

        return $this->articleRepository->insert([
            ArticleRepository::COL_AUTHOR_ID => $loggedInUser->id,
            ArticleRepository::COL_TITLE => $articleDto->title,
            ArticleRepository::COL_CONTENT => $articleDto->content,
        ]);
    }
}
