<?php

declare(strict_types=1);

namespace App\Model\Article\Handler;

use App\Core\Api\Auth\Authorizator;
use App\Model\Article\ArticleRepository;
use App\Model\Article\Dto\ArticleUpdateDto;
use App\Model\Exception\EntityNotFoundException;
use App\Model\User\UserRole;

final readonly class ArticleUpdateHandler
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private Authorizator $authorizator,
    ) {
    }

    public function update(ArticleUpdateDto $articleDto): int
    {
        $article = $this->articleRepository->findById($articleDto->id);

        if (! $article) {
            throw new EntityNotFoundException('Article not found');
        }

        $this->authorizator->requireOwnerOrRole($article, UserRole::ADMIN);

        return $this->articleRepository->update($article->id, [
            ArticleRepository::COL_TITLE => $articleDto->title,
            ArticleRepository::COL_CONTENT => $articleDto->content,
        ]);
    }
}
