<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240828212446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE iesire (id INT AUTO_INCREMENT NOT NULL, produs_id INT NOT NULL, data DATE NOT NULL, nr_doc_iesire VARCHAR(255) NOT NULL, stoc_iesire INT NOT NULL, iesiri INT NOT NULL, INDEX IDX_D1B67672C7FC4C39 (produs_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intrare (id INT AUTO_INCREMENT NOT NULL, produs_id INT NOT NULL, data DATE NOT NULL, nr_doc_intrare VARCHAR(255) NOT NULL, intrari INT NOT NULL, nefolosibile INT NOT NULL, stoc_intrare INT NOT NULL, INDEX IDX_151CB9CEC7FC4C39 (produs_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produs (id INT AUTO_INCREMENT NOT NULL, nume VARCHAR(255) NOT NULL, stoc INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, test1 VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE iesire ADD CONSTRAINT FK_D1B67672C7FC4C39 FOREIGN KEY (produs_id) REFERENCES produs (id)');
        $this->addSql('ALTER TABLE intrare ADD CONSTRAINT FK_151CB9CEC7FC4C39 FOREIGN KEY (produs_id) REFERENCES produs (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE iesire DROP FOREIGN KEY FK_D1B67672C7FC4C39');
        $this->addSql('ALTER TABLE intrare DROP FOREIGN KEY FK_151CB9CEC7FC4C39');
        $this->addSql('DROP TABLE iesire');
        $this->addSql('DROP TABLE intrare');
        $this->addSql('DROP TABLE produs');
        $this->addSql('DROP TABLE test');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
