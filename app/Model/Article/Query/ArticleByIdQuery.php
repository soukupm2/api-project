<?php

declare(strict_types=1);

namespace App\Model\Article\Query;

use App\Model\Article\Article;
use App\Model\Article\ArticleRepository;

final readonly class ArticleByIdQuery
{
    public function __construct(
        private ArticleRepository $articleRepository
    ) {
    }

    public function fetch(int $id): ?Article
    {
        return $this->articleRepository->findById($id);
    }
}
