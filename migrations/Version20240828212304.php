<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240828212304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE iesire (id INT AUTO_INCREMENT NOT NULL, produs_id INT NOT NULL, data DATE NOT NULL, nr_doc_iesire VARCHAR(255) NOT NULL, stoc_iesire INT NOT NULL, iesiri INT NOT NULL, INDEX IDX_D1B67672C7FC4C39 (produs_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE iesire ADD CONSTRAINT FK_D1B67672C7FC4C39 FOREIGN KEY (produs_id) REFERENCES produs (id)');
        $this->addSql('ALTER TABLE intrare ADD stoc_intrare INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE iesire DROP FOREIGN KEY FK_D1B67672C7FC4C39');
        $this->addSql('DROP TABLE iesire');
        $this->addSql('ALTER TABLE intrare DROP stoc_intrare');
    }
}
