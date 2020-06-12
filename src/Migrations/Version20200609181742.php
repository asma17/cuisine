<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200609181742 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ingredient_recette ADD recette_id INT NOT NULL');
        $this->addSql('ALTER TABLE ingredient_recette ADD CONSTRAINT FK_488C685689312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id)');
        $this->addSql('CREATE INDEX IDX_488C685689312FE9 ON ingredient_recette (recette_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ingredient_recette DROP FOREIGN KEY FK_488C685689312FE9');
        $this->addSql('DROP INDEX IDX_488C685689312FE9 ON ingredient_recette');
        $this->addSql('ALTER TABLE ingredient_recette DROP recette_id');
    }
}
