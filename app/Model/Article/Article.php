<?php

declare(strict_types=1);

namespace App\Model\Article;

use App\Model\UserOwnedEntity;
use Nette\Database\Table\ActiveRow;

final readonly class Article implements UserOwnedEntity
{
    public function __construct(
        public int $id,
        public int $authorId,
        public string $title,
        public string $content,
    ) {
    }

    public function getOwnerId(): int
    {
        return $this->authorId;
    }

    public static function fromRow(ActiveRow $row): self
    {
        return new self(
            // @phpstan-ignore-next-line
            id: $row[ArticleRepository::COL_ID],
            // @phpstan-ignore-next-line
            authorId: $row[ArticleRepository::COL_AUTHOR_ID],
            // @phpstan-ignore-next-line
            title: $row[ArticleRepository::COL_TITLE],
            // @phpstan-ignore-next-line
            content: $row[ArticleRepository::COL_CONTENT],
        );
    }

    /**
     * @return array<string, string|int>
     */
    public function toResponse(): array
    {
        return [
            'id' => $this->id,
            'author_id' => $this->authorId,
            'title' => $this->title,
            'content' => $this->content,
        ];
    }
}
