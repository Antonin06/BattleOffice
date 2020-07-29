<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200729074051 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` CHANGE client_id client_id INT DEFAULT NULL, CHANGE product_id product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shipping ADD client_id INT DEFAULT NULL, CHANGE country_id country_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shipping ADD CONSTRAINT FK_2D1C172419EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2D1C172419EB6921 ON shipping (client_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` CHANGE client_id client_id INT NOT NULL, CHANGE product_id product_id INT NOT NULL');
        $this->addSql('ALTER TABLE shipping DROP FOREIGN KEY FK_2D1C172419EB6921');
        $this->addSql('DROP INDEX UNIQ_2D1C172419EB6921 ON shipping');
        $this->addSql('ALTER TABLE shipping DROP client_id, CHANGE country_id country_id INT NOT NULL');
    }
}
