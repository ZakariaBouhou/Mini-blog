<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220313140722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66EE45BDBF');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP INDEX UNIQ_23A0E66EE45BDBF ON article');
        $this->addSql('ALTER TABLE article DROP picture_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, picture VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE article ADD picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66EE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_23A0E66EE45BDBF ON article (picture_id)');
    }
}
