<?php

declare(strict_types=1);

namespace App\Model\Article\Dto;

use Nette\Utils\Json;

final readonly class ArticleUpdateDto
{
    public function __construct(
        public int $id,
        public string $title,
        public string $content,
    ) {
    }

    public static function fromRequestData(int $articleId, string $body): self
    {
        $data = Json::decode($body, true);

        // @phpstan-ignore-next-line
        return new self(id: $articleId, title: $data['title'], content: $data['content']);
    }
}
