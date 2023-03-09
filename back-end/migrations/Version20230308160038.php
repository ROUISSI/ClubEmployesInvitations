<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230308160038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invitation CHANGE sender sender INT DEFAULT NULL, CHANGE receiver receiver INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A25F004ACF FOREIGN KEY (sender) REFERENCES user (id)');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A23DB88C96 FOREIGN KEY (receiver) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F11D61A25F004ACF ON invitation (sender)');
        $this->addSql('CREATE INDEX IDX_F11D61A23DB88C96 ON invitation (receiver)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE status');
        $this->addSql('ALTER TABLE invitation DROP FOREIGN KEY FK_F11D61A25F004ACF');
        $this->addSql('ALTER TABLE invitation DROP FOREIGN KEY FK_F11D61A23DB88C96');
        $this->addSql('DROP INDEX IDX_F11D61A25F004ACF ON invitation');
        $this->addSql('DROP INDEX IDX_F11D61A23DB88C96 ON invitation');
        $this->addSql('ALTER TABLE invitation CHANGE sender sender VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE receiver receiver VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
