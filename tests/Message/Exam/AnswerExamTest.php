<?php
declare(strict_types=1);

namespace App\Tests\Message\Exam;

use App\Message\Exam\AnswerExam;
use PHPUnit\Framework\TestCase;

class AnswerExamTest extends TestCase
{
    public function testCreateAnswerExam()
    {
        $answers = [
            10 => 'obiwan kenoby',
            132 => 'dark vador'
        ];
        $answerExam = new AnswerExam(42, 69, $answers);

        self::assertEquals(42, $answerExam->getExamId());
        self::assertEquals(69, $answerExam->getStudentId());
        self::assertCount(2, $answerExam->getAnswers());
    }
}
