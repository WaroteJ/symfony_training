<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191215214950 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, id_todolist_id INT NOT NULL, content LONGTEXT NOT NULL, checked TINYINT(1) NOT NULL, deleted TINYINT(1) NOT NULL, INDEX IDX_527EDB25B504A9DD (id_todolist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25B504A9DD FOREIGN KEY (id_todolist_id) REFERENCES todolist (id)');
        $this->addSql('ALTER TABLE todolist ADD id_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE todolist ADD CONSTRAINT FK_DD4DF6DB79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DD4DF6DB79F37AE5 ON todolist (id_user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE task');
        $this->addSql('ALTER TABLE todolist DROP FOREIGN KEY FK_DD4DF6DB79F37AE5');
        $this->addSql('DROP INDEX IDX_DD4DF6DB79F37AE5 ON todolist');
        $this->addSql('ALTER TABLE todolist DROP id_user_id');
    }
}
