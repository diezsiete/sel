<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191130001339 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE scraper_message_hv (id INT AUTO_INCREMENT NOT NULL, hv_id INT DEFAULT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_475F554BB83428F3 (hv_id), INDEX idx_message_queue_success (queue_name), INDEX idx_message_available_at_success (available_at), INDEX idx_message_delivered_at_success (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scraper_message_hv_success (id INT AUTO_INCREMENT NOT NULL, hv_id INT DEFAULT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_959181FB83428F3 (hv_id), INDEX idx_message_success_queue_success (queue_name), INDEX idx_message_success_available_at_success (available_at), INDEX idx_message_success_delivered_at_success (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE scraper_message_hv ADD CONSTRAINT FK_475F554BB83428F3 FOREIGN KEY (hv_id) REFERENCES hv (id)');
        $this->addSql('ALTER TABLE scraper_message_hv_success ADD CONSTRAINT FK_959181FB83428F3 FOREIGN KEY (hv_id) REFERENCES hv (id)');
        $this->addSql('DROP TABLE scraper_message');
        $this->addSql('DROP TABLE scraper_message_success');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE scraper_message (id INT AUTO_INCREMENT NOT NULL, hv_id INT DEFAULT NULL, body LONGTEXT NOT NULL COLLATE utf8mb4_general_ci, headers LONGTEXT NOT NULL COLLATE utf8mb4_general_ci, queue_name VARCHAR(255) NOT NULL COLLATE utf8mb4_general_ci, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX idx_message_queue_success (queue_name(191)), INDEX idx_message_delivered_at_success (delivered_at), INDEX IDX_E97F8897B83428F3 (hv_id), INDEX idx_message_available_at_success (available_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE scraper_message_success (id INT AUTO_INCREMENT NOT NULL, hv_id INT DEFAULT NULL, body LONGTEXT NOT NULL COLLATE utf8mb4_general_ci, headers LONGTEXT NOT NULL COLLATE utf8mb4_general_ci, queue_name VARCHAR(255) NOT NULL COLLATE utf8mb4_general_ci, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX idx_message_success_queue_success (queue_name(191)), INDEX idx_message_success_delivered_at_success (delivered_at), INDEX IDX_5A443D08B83428F3 (hv_id), INDEX idx_message_success_available_at_success (available_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE scraper_message ADD CONSTRAINT FK_E97F8897B83428F3 FOREIGN KEY (hv_id) REFERENCES hv (id)');
        $this->addSql('ALTER TABLE scraper_message_success ADD CONSTRAINT FK_5A443D08B83428F3 FOREIGN KEY (hv_id) REFERENCES hv (id)');
        $this->addSql('DROP TABLE scraper_message_hv');
        $this->addSql('DROP TABLE scraper_message_hv_success');
    }
}
