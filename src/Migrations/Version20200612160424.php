<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200612160424 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ingredient_recette ADD CONSTRAINT FK_488C6856EC4A74AB FOREIGN KEY (unite_id) REFERENCES unite (id)');
        $this->addSql('CREATE INDEX IDX_488C6856EC4A74AB ON ingredient_recette (unite_id)');
        $this->addSql('ALTER TABLE recette CHANGE titre titre VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ingredient_recette DROP FOREIGN KEY FK_488C6856EC4A74AB');
        $this->addSql('DROP INDEX IDX_488C6856EC4A74AB ON ingredient_recette');
        $this->addSql('ALTER TABLE recette CHANGE titre titre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
