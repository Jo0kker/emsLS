<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190828095006 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE clients (id INT AUTO_INCREMENT NOT NULL, mutuelle_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, birth_date VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, obs VARCHAR(255) NOT NULL, emploi VARCHAR(255) NOT NULL, ppa TINYINT(1) NOT NULL, nb_inter INT NOT NULL, INDEX IDX_C82E74C6DA041E (mutuelle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clients ADD CONSTRAINT FK_C82E74C6DA041E FOREIGN KEY (mutuelle_id) REFERENCES mutuelle (id)');
        $this->addSql('ALTER TABLE mutuelle CHANGE prix prix INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE clients');
        $this->addSql('ALTER TABLE mutuelle CHANGE prix prix VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
