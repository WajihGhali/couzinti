<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191208152416 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rank DROP FOREIGN KEY FK_8879E8E54C87C8E1');
        $this->addSql('ALTER TABLE rank ADD CONSTRAINT FK_8879E8E54C87C8E1 FOREIGN KEY (rank_recipe_id) REFERENCES recipe (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rank DROP FOREIGN KEY FK_8879E8E54C87C8E1');
        $this->addSql('ALTER TABLE rank ADD CONSTRAINT FK_8879E8E54C87C8E1 FOREIGN KEY (rank_recipe_id) REFERENCES user (id)');
    }
}
