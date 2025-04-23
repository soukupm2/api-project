<?php

declare(strict_types=1);

namespace App\Model\Article;

use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;

final readonly class ArticleRepository
{
    public const TABLE = 'article';

    public const COL_ID = 'id';

    public const COL_AUTHOR_ID = 'author_id';

    public const COL_TITLE = 'title';

    public const COL_CONTENT = 'content';

    public function __construct(
        private Explorer $db
    ) {
    }

    /**
     * @return Article[]
     */
    public function findAll(): array
    {
        $rows = $this->db->table(self::TABLE)->fetchAll();

        return array_map(static fn (ActiveRow $row) => Article::fromRow($row), array_values($rows));
    }

    public function findById(int $id): ?Article
    {
        $user = $this->db->table(self::TABLE)
            ->where(self::COL_ID, $id)
            ->fetch();

        return $user === null ? null : Article::fromRow($user);
    }

    public function delete(int $id): int
    {
        /** @var int $deletedCount */
        $deletedCount = $this->db->table(self::TABLE)
            ->where(self::COL_ID, $id)
            ->delete();

        return $deletedCount;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function insert(array $data): Article
    {
        /** @var ActiveRow $row */
        $row = $this->db->table(self::TABLE)->insert($data);

        return Article::fromRow($row);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): int
    {
        /** @var int $updatedCount */
        $updatedCount = $this->db->table(self::TABLE)
            ->where(self::COL_ID, $id)
            ->update($data);

        return $updatedCount;
    }
}
