<?php

declare(strict_types=1);

namespace Subtext\AppFactory\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201216172305 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create the forms table in the database';
    }

    public function up(Schema $schema) : void
    {
        $table = $schema->createTable('forms');
        $table->addColumn('form_id', 'integer', ['unsigned' => true, 'autoincrement' => true]);
        $table->addColumn('form_name', 'string', ['length' => 64, 'notnull' => true]);
        $table->addColumn('form_desc', 'string', ['notnull' => true]);
        $table->addColumn('form_attr_id', 'string');
        $table->addColumn('form_attr_class', 'string');
        $table->setPrimaryKey(['form_id']);
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('forms');
    }
}
