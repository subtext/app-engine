<?php

declare(strict_types=1);

namespace Subtext\AppFactory\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201216222133 extends AbstractMigration
{
    const INPUT_TYPES = [
        'button', 'checkbox', 'color', 'data', 'datetime-local', 'email',
        'file', 'hidden', 'image', 'month', 'number', 'password', 'radio',
        'range', 'reset', 'search', 'submit', 'tel', 'text', 'time', 'url',
        'week'
    ];

    const TYPES_SQL = "INSERT INTO `input_types` (`input_type_value`) VALUE (:type)";

    public function getDescription() : string
    {
        return 'Create the Fieldsets, Inputs, and Input_Types tables in the database';
    }

    public function up(Schema $schema) : void
    {
        $fieldsets = $schema->createTable('fieldsets');
        $fieldsets->addColumn('fieldset_id', 'integer', ['unsigned' => true, 'autoincrement' => true]);
        $fieldsets->addColumn('fieldset_legend', 'string', ['length' => 64, 'default' => null]);
        $fieldsets->addColumn('form_id', 'integer', ['unsigned' => true, 'notnull' => true]);
        $fieldsets->addForeignKeyConstraint('forms', ['form_id'], ['form_id']);
        $fieldsets->addIndex(['form_id']);
        $fieldsets->setPrimaryKey(['fieldset_id']);

        $inputs = $schema->createTable('inputs');
        $inputs->addColumn('input_id', 'integer', ['unsigned' => true, 'autoincrement' => true]);
        $inputs->addColumn('input_type', 'integer', ['unsigned' => true, 'notnull' => true]);
        $inputs->addColumn('input_attributes', 'json', ['default' => null]);
        $inputs->addColumn('fieldset_id', 'integer', ['unsigned' => true]);
        $inputs->addForeignKeyConstraint('fieldsets', ['fieldset_id'], ['fieldset_id']);
        $inputs->addIndex(['fieldset_id']);
        $inputs->setPrimaryKey(['input_id']);

        $types = $schema->createTable('input_types');
        $types->addColumn('input_type_id', 'integer', ['unsigned' => true, 'autoincrement' => true]);
        $types->addColumn('input_type_value', 'string', ['length' => 32, 'notnull' => true]);
        $types->setPrimaryKey(['input_type_id']);
    }

    public function postUp(Schema $schema): void
    {
        parent::postUp($schema);
        $this->connection->beginTransaction();
        try {
            foreach (self::INPUT_TYPES as $type) {
                $this->connection->executeQuery(self::TYPES_SQL, ['type' => $type]);
            }
        } catch (\Throwable $e) {
            $this->connection->rollBack();
            $this->abortIf(true, $e->getMessage());
        }
        $this->connection->commit();
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('inputs');
        $schema->dropTable('input_types');
        $schema->dropTable('fieldsets');
    }
}
