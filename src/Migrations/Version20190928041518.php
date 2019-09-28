<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190928041518 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sms_service_provider (id INT AUTO_INCREMENT NOT NULL, alias VARCHAR(255) NOT NULL, total_call INT DEFAULT NULL, failed_calls INT DEFAULT NULL, gateway VARCHAR(255) NOT NULL, status TINYINT(1) NOT NULL, score INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sms (id INT AUTO_INCREMENT NOT NULL, sms_service_provider_id INT DEFAULT NULL, unique_id VARCHAR(255) NOT NULL, number INT NOT NULL, text VARCHAR(255) NOT NULL, timestamp INT NOT NULL, status INT NOT NULL, INDEX IDX_B0A93A7754B02A04 (sms_service_provider_id), INDEX number_idx (number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sms ADD CONSTRAINT FK_B0A93A7754B02A04 FOREIGN KEY (sms_service_provider_id) REFERENCES sms_service_provider (id)');
        $this->addSql('INSERT INTO sms_service_provider (id, alias, gateway, status, score) VALUE (1, \'api-1\', \'http://127.0.0.1:81/sms/send\', 1, 100) ON DUPLICATE KEY UPDATE alias=\'api-1\';');

        $this->addSql('INSERT INTO sms_service_provider (id, alias, gateway, status, score) VALUE (2, \'api-2\', \'http://127.0.0.1:82/sms/send\', 1, 100) ON DUPLICATE KEY UPDATE alias=\'api-2\';');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sms DROP FOREIGN KEY FK_B0A93A7754B02A04');
        $this->addSql('DROP TABLE sms_service_provider');
        $this->addSql('DROP TABLE sms');
    }
}
