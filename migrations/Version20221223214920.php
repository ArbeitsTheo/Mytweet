<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221223214920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE tweet_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tweet (id INT NOT NULL, user_id_id INT NOT NULL, parent_id_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3D660A3B9D86650F ON tweet (user_id_id)');
        $this->addSql('CREATE INDEX IDX_3D660A3BB3750AF4 ON tweet (parent_id_id)');
        $this->addSql('ALTER TABLE tweet ADD CONSTRAINT FK_3D660A3B9D86650F FOREIGN KEY (user_id_id) REFERENCES "utilisateur" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tweet ADD CONSTRAINT FK_3D660A3BB3750AF4 FOREIGN KEY (parent_id_id) REFERENCES tweet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE tweet_id_seq CASCADE');
        $this->addSql('ALTER TABLE tweet DROP CONSTRAINT FK_3D660A3B9D86650F');
        $this->addSql('ALTER TABLE tweet DROP CONSTRAINT FK_3D660A3BB3750AF4');
        $this->addSql('DROP TABLE tweet');
    }
}
