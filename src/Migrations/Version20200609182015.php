<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200609182015 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ingredient_recette ADD ingredient_id INT NOT NULL');
        $this->addSql('ALTER TABLE ingredient_recette ADD CONSTRAINT FK_488C6856933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('CREATE INDEX IDX_488C6856933FE08C ON ingredient_recette (ingredient_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ingredient_recette DROP FOREIGN KEY FK_488C6856933FE08C');
        $this->addSql('DROP INDEX IDX_488C6856933FE08C ON ingredient_recette');
        $this->addSql('ALTER TABLE ingredient_recette DROP ingredient_id');
    }
}
