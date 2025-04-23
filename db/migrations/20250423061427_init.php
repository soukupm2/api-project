<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Init extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $this->execute(<<<SQL
            CREATE TABLE "user" (
               id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
               email varchar(180) NOT NULL UNIQUE,
               password_hash varchar(255) NOT NULL,
               name varchar(255) NOT NULL,
               role varchar(20) NOT NULL CHECK (role IN ('admin', 'author', 'reader'))
            );

            CREATE TABLE article (
                id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                author_id integer NOT NULL,
                title varchar(255) NOT NULL,
                content text NOT NULL,
                FOREIGN KEY (author_id) REFERENCES "user"(id) ON DELETE RESTRICT
            );
        SQL);

        $this->execute(<<<SQL
            INSERT INTO "user" (email, password_hash, name, role)
            VALUES ('admin@test.com', '$2y$10\$o/asH7ojuUBs60KpkvSNgu89laPpqVU4.cGZfHgSLfhmFfmOe1jmi', 'Admin Test', 'admin');

            INSERT INTO "user" (email, password_hash, name, role)
            VALUES ('author@test.com', '$2y$10\$STPeVXzL8H6PkbIVZ01iHO9hq5YLzi1yO9Kbd7NnNhEKXdih9zYpO', 'Author Test', 'author');

            INSERT INTO "user" (email, password_hash, name, role)
            VALUES ('reader@test.com', '$2y$10\$f5W993uaCRwC7XokDH/ctexZQ8J6WmkL2d1ter5D47ZB4Bs0U5wFq', 'Reader Test', 'c');
        SQL);
    }
}
