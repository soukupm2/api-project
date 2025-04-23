<?php

declare(strict_types=1);

namespace App\Model\Article\Handler;

use App\Core\Api\Auth\Authorizator;
use App\Model\Article\ArticleRepository;
use App\Model\Exception\EntityNotFoundException;
use App\Model\User\UserRole;

final readonly class ArticleDeleteHandler
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private Authorizator $authorizator,
    ) {
    }

    public function delete(int $id): int
    {
        $article = $this->articleRepository->findById($id);

        if (! $article) {
            throw new EntityNotFoundException('Article not found');
        }

        $this->authorizator->requireOwnerOrRole($article, UserRole::ADMIN);

        return $this->articleRepository->delete($article->id);
    }
}
