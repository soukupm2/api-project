<?php

declare(strict_types=1);

namespace App\Model\User;

use Nette\Database\Table\ActiveRow;

final readonly class User
{
    public function __construct(
        public int $id,
        public string $email,
        public string $name,
        public UserRole $role,
        public string $passwordHash,
    ) {
    }

    public static function fromRow(ActiveRow $row): self
    {
        return new self(
            // @phpstan-ignore-next-line
            $row[UserRepository::COL_ID],
            // @phpstan-ignore-next-line
            $row[UserRepository::COL_EMAIL],
            // @phpstan-ignore-next-line
            $row[UserRepository::COL_NAME],
            // @phpstan-ignore-next-line
            UserRole::from($row[UserRepository::COL_ROLE]),
            // @phpstan-ignore-next-line
            $row[UserRepository::COL_PASSWORD_HASH],
        );
    }

    /**
     * @return array<string, string|int>
     */
    public function toResponse(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'role' => $this->role->value,
        ];
    }
}
