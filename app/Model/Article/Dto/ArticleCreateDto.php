<?php

declare(strict_types=1);

namespace App\Model\Article\Dto;

use Nette\Utils\Json;

final readonly class ArticleCreateDto
{
    public function __construct(
        public string $title,
        public string $content,
    ) {
    }

    public static function fromRequestData(string $body): self
    {
        $data = Json::decode($body, true);

        // @phpstan-ignore-next-line
        return new self(title: $data['title'], content: $data['content']);
    }
}
