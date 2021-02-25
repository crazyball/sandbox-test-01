<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210225175846 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE exam_session_answer (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, exam_session_id INT DEFAULT NULL, answer VARCHAR(250) NOT NULL, is_valid TINYINT(1) NOT NULL, INDEX IDX_2B63A92A1E27F6BF (question_id), INDEX IDX_2B63A92A7337968A (exam_session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exam_session_answer ADD CONSTRAINT FK_2B63A92A1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE exam_session_answer ADD CONSTRAINT FK_2B63A92A7337968A FOREIGN KEY (exam_session_id) REFERENCES exam_session (id)');
        $this->addSql('ALTER TABLE exam_session DROP FOREIGN KEY FK_DDE28CBC1E27F6BF');
        $this->addSql('DROP INDEX IDX_DDE28CBC1E27F6BF ON exam_session');
        $this->addSql('ALTER TABLE exam_session DROP question_id, DROP answer, DROP is_valid');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE exam_session_answer');
        $this->addSql('ALTER TABLE exam_session ADD question_id INT DEFAULT NULL, ADD answer VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD is_valid TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE exam_session ADD CONSTRAINT FK_DDE28CBC1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_DDE28CBC1E27F6BF ON exam_session (question_id)');
    }
}
