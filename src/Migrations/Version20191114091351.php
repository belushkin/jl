<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191114091351 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Tax (id INT AUTO_INCREMENT NOT NULL, county_id INT NOT NULL, state_id INT NOT NULL, country_id INT DEFAULT NULL, amount INT NOT NULL, INDEX IDX_B6CCFC9685E73F45 (county_id), INDEX IDX_B6CCFC965D83CC1 (state_id), INDEX IDX_B6CCFC96F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE County (id INT AUTO_INCREMENT NOT NULL, state_id INT NOT NULL, name VARCHAR(255) NOT NULL, tax_rate INT NOT NULL, INDEX IDX_5F4EFA135D83CC1 (state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE State (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_6252FDFFF92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Tax ADD CONSTRAINT FK_B6CCFC9685E73F45 FOREIGN KEY (county_id) REFERENCES County (id)');
        $this->addSql('ALTER TABLE Tax ADD CONSTRAINT FK_B6CCFC965D83CC1 FOREIGN KEY (state_id) REFERENCES State (id)');
        $this->addSql('ALTER TABLE Tax ADD CONSTRAINT FK_B6CCFC96F92F3E70 FOREIGN KEY (country_id) REFERENCES Country (id)');
        $this->addSql('ALTER TABLE County ADD CONSTRAINT FK_5F4EFA135D83CC1 FOREIGN KEY (state_id) REFERENCES State (id)');
        $this->addSql('ALTER TABLE State ADD CONSTRAINT FK_6252FDFFF92F3E70 FOREIGN KEY (country_id) REFERENCES Country (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Tax DROP FOREIGN KEY FK_B6CCFC96F92F3E70');
        $this->addSql('ALTER TABLE State DROP FOREIGN KEY FK_6252FDFFF92F3E70');
        $this->addSql('ALTER TABLE Tax DROP FOREIGN KEY FK_B6CCFC9685E73F45');
        $this->addSql('ALTER TABLE Tax DROP FOREIGN KEY FK_B6CCFC965D83CC1');
        $this->addSql('ALTER TABLE County DROP FOREIGN KEY FK_5F4EFA135D83CC1');
        $this->addSql('DROP TABLE Tax');
        $this->addSql('DROP TABLE Country');
        $this->addSql('DROP TABLE County');
        $this->addSql('DROP TABLE State');
    }
}
