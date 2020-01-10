<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191226154918 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25B504A9DD');
        $this->addSql('DROP INDEX IDX_527EDB25B504A9DD ON task');
        $this->addSql('ALTER TABLE task CHANGE id_todolist_id todolist_id INT NOT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25AD16642A FOREIGN KEY (todolist_id) REFERENCES todolist (id)');
        $this->addSql('CREATE INDEX IDX_527EDB25AD16642A ON task (todolist_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25AD16642A');
        $this->addSql('DROP INDEX IDX_527EDB25AD16642A ON task');
        $this->addSql('ALTER TABLE task CHANGE todolist_id id_todolist_id INT NOT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25B504A9DD FOREIGN KEY (id_todolist_id) REFERENCES todolist (id)');
        $this->addSql('CREATE INDEX IDX_527EDB25B504A9DD ON task (id_todolist_id)');
    }
}
