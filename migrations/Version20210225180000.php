<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210225180000 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exam_session ADD student_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exam_session ADD CONSTRAINT FK_DDE28CBCCB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('CREATE INDEX IDX_DDE28CBCCB944F1A ON exam_session (student_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exam_session DROP FOREIGN KEY FK_DDE28CBCCB944F1A');
        $this->addSql('DROP INDEX IDX_DDE28CBCCB944F1A ON exam_session');
        $this->addSql('ALTER TABLE exam_session DROP student_id');
    }
}
