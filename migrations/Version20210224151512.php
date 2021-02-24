<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210224151512 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE exam (id INT AUTO_INCREMENT NOT NULL, classroom_id INT DEFAULT NULL, INDEX IDX_38BBA6C66278D5A8 (classroom_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exam_question (exam_id INT NOT NULL, question_id INT NOT NULL, INDEX IDX_F593067D578D5E91 (exam_id), INDEX IDX_F593067D1E27F6BF (question_id), PRIMARY KEY(exam_id, question_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exam_session (id INT AUTO_INCREMENT NOT NULL, exam_id INT DEFAULT NULL, question_id INT DEFAULT NULL, answer VARCHAR(250) NOT NULL, is_valid TINYINT(1) NOT NULL, INDEX IDX_DDE28CBC578D5E91 (exam_id), INDEX IDX_DDE28CBC1E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, question VARCHAR(250) NOT NULL, answer VARCHAR(250) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C66278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('ALTER TABLE exam_question ADD CONSTRAINT FK_F593067D578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exam_question ADD CONSTRAINT FK_F593067D1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exam_session ADD CONSTRAINT FK_DDE28CBC578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
        $this->addSql('ALTER TABLE exam_session ADD CONSTRAINT FK_DDE28CBC1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exam_question DROP FOREIGN KEY FK_F593067D578D5E91');
        $this->addSql('ALTER TABLE exam_session DROP FOREIGN KEY FK_DDE28CBC578D5E91');
        $this->addSql('ALTER TABLE exam_question DROP FOREIGN KEY FK_F593067D1E27F6BF');
        $this->addSql('ALTER TABLE exam_session DROP FOREIGN KEY FK_DDE28CBC1E27F6BF');
        $this->addSql('DROP TABLE exam');
        $this->addSql('DROP TABLE exam_question');
        $this->addSql('DROP TABLE exam_session');
        $this->addSql('DROP TABLE question');
    }
}
