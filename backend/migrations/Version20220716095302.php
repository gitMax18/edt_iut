<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220716095302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD teacher_id INT DEFAULT NULL, ADD course_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA741807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA741807E1D ON event (teacher_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7591CC992 ON event (course_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA741807E1D');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7591CC992');
        $this->addSql('DROP INDEX IDX_3BAE0AA741807E1D ON event');
        $this->addSql('DROP INDEX IDX_3BAE0AA7591CC992 ON event');
        $this->addSql('ALTER TABLE event DROP teacher_id, DROP course_id');
    }
}
