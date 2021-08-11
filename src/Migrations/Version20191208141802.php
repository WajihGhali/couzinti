<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191208141802 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rank ADD rank_user_id INT DEFAULT NULL, ADD rank_recipe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rank ADD CONSTRAINT FK_8879E8E585E256C7 FOREIGN KEY (rank_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rank ADD CONSTRAINT FK_8879E8E54C87C8E1 FOREIGN KEY (rank_recipe_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8879E8E585E256C7 ON rank (rank_user_id)');
        $this->addSql('CREATE INDEX IDX_8879E8E54C87C8E1 ON rank (rank_recipe_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rank DROP FOREIGN KEY FK_8879E8E585E256C7');
        $this->addSql('ALTER TABLE rank DROP FOREIGN KEY FK_8879E8E54C87C8E1');
        $this->addSql('DROP INDEX IDX_8879E8E585E256C7 ON rank');
        $this->addSql('DROP INDEX IDX_8879E8E54C87C8E1 ON rank');
        $this->addSql('ALTER TABLE rank DROP rank_user_id, DROP rank_recipe_id');
    }
}
