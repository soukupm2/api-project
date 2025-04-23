<?php

declare(strict_types=1);

namespace App\Model\User;

use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;

final readonly class UserRepository
{
    public const TABLE = 'user';

    public const COL_ID = 'id';

    public const COL_EMAIL = 'email';

    public const COL_NAME = 'name';

    public const COL_PASSWORD_HASH = 'password_hash';

    public const COL_ROLE = 'role';

    public function __construct(
        private Explorer $db
    ) {
    }

    /**
     * @return User[]
     */
    public function findAll(): array
    {
        $rows = $this->db->table(self::TABLE)->fetchAll();

        return array_map(static fn (ActiveRow $row) => User::fromRow($row), array_values($rows));
    }

    public function findByEmail(string $email): ?User
    {
        $user = $this->db->table(self::TABLE)
            ->where(self::COL_EMAIL, $email)
            ->fetch();

        return $user === null ? null : User::fromRow($user);
    }

    public function findById(int $id): ?User
    {
        $user = $this->db->table(self::TABLE)
            ->where(self::COL_ID, $id)
            ->fetch();

        return $user === null ? null : User::fromRow($user);
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
    public function insert(array $data): User
    {
        /** @var ActiveRow $row */
        $row = $this->db->table(self::TABLE)->insert($data);

        return User::fromRow($row);
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
