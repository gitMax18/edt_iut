<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220802062546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation ADD responsable_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF53C59D72 FOREIGN KEY (responsable_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_404021BF53C59D72 ON formation (responsable_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF53C59D72');
        $this->addSql('DROP INDEX UNIQ_404021BF53C59D72 ON formation');
        $this->addSql('ALTER TABLE formation DROP responsable_id');
    }
}
