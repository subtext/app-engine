<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Books extends AbstractMigration
{
    private const TABLE = 'books';

    public int $book_id;
    public string $book_title;
    public string $book_isbn;
    public string $book_author;

    public function up(): void
    {
        if (!$this->hasTable(self::TABLE)) {
            $this->table(self::TABLE, [
                'id' => 'book_id',
                'signed' => false,
                'engine' => 'InnoDB'
            ])->addColumn(
                'book_title',
                'string',
                [
                    'limit' => 256,
                    'null' => true,
                    'default' => null,
                ]
            )->addColumn(
                'book_isbn',
                'string',
                [
                    'limit' => 16,
                    'null' => true,
                    'default' => null,
                ]
            )->addColumn(
                'book_author',
                'string',
                [
                    'limit' => 128,
                    'null' => true,
                    'default' => null,
                ]
            )->create();
        }
    }

    public function down(): void
    {
        if ($this->hasTable(self::TABLE)) {
            $this->table(self::TABLE)->drop()->save();
        }
    }
}