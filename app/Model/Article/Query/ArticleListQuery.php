<?php

declare(strict_types=1);

namespace App\Model\Article\Query;

use App\Model\Article\Article;
use App\Model\Article\ArticleRepository;

final readonly class ArticleListQuery
{
    public function __construct(
        private ArticleRepository $articleRepository
    ) {
    }

    /**
     * @return Article[]
     */
    public function fetch(): array
    {
        return $this->articleRepository->findAll();
    }
}
