<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221114124157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD post_commented_id INT NOT NULL, ADD published_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C6A9ADC72 FOREIGN KEY (post_commented_id) REFERENCES post (id)');
        $this->addSql('CREATE INDEX IDX_9474526C6A9ADC72 ON comment (post_commented_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C6A9ADC72');
        $this->addSql('DROP INDEX IDX_9474526C6A9ADC72 ON comment');
        $this->addSql('ALTER TABLE comment DROP post_commented_id, DROP published_at');
    }
}
