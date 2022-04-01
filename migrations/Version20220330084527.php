<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220330084527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (articleid INT AUTO_INCREMENT NOT NULL, userid INT DEFAULT NULL, image_id INT DEFAULT NULL, title VARCHAR(64) DEFAULT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_23A0E663DA5256D (image_id), INDEX userid (userid), PRIMARY KEY(articleid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE belong_to (articleid INT NOT NULL, categoryid INT NOT NULL, INDEX IDX_C86E276D6B26844C (articleid), INDEX IDX_C86E276D9B32FD3 (categoryid), PRIMARY KEY(articleid, categoryid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (categoryid INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) DEFAULT NULL, PRIMARY KEY(categoryid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (userid INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(64) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(userid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66F132696E FOREIGN KEY (userid) REFERENCES user (userid)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E663DA5256D FOREIGN KEY (image_id) REFERENCES picture (id)');
        $this->addSql('ALTER TABLE belong_to ADD CONSTRAINT FK_C86E276D6B26844C FOREIGN KEY (articleid) REFERENCES article (articleid)');
        $this->addSql('ALTER TABLE belong_to ADD CONSTRAINT FK_C86E276D9B32FD3 FOREIGN KEY (categoryid) REFERENCES category (categoryid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE belong_to DROP FOREIGN KEY FK_C86E276D6B26844C');
        $this->addSql('ALTER TABLE belong_to DROP FOREIGN KEY FK_C86E276D9B32FD3');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E663DA5256D');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66F132696E');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE belong_to');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE user');
    }
}
