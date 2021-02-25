<?php

namespace App\Tests\MessageHandler\Exam;

use App\Entity\Classroom;
use App\Entity\Exam;
use App\Entity\Question;
use App\Entity\Student;
use App\Message\Exam\AnswerExam;
use App\MessageHandler\Exam\AnswerExamHandler;
use App\Repository\ExamRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\EventDispatcher\EventDispatcherInterface;

class AnswerExamHandlerTest extends TestCase
{
    use ProphecyTrait;

    private $examRepository;

    private AnswerExamHandler $answerExamHandler;

    public function testAnswerToNonExistantExam(): void
    {
        $answerExam = new AnswerExam(42, 69, $this->getAnswers());

        $this
            ->examRepository
            ->find($answerExam->getExamId())
            ->shouldBeCalledOnce()
            ->willReturn(null);

        $this->expectException(\RuntimeException::class);

        $this->answerExamHandler->__invoke($answerExam);
    }

    public function testAnswerToNonAssociatedStudent(): void
    {
        $answerExam = new AnswerExam(42, 69, $this->getAnswers());

        $dbStudent = new Student();
        $dbStudent->setId(50);

        $dbClassroom = new Classroom();
        $dbClassroom->setStudents([$dbStudent]);

        $dbExam = new Exam();
        $dbExam->setClassroom($dbClassroom);

        $this
            ->examRepository
            ->find($answerExam->getExamId())
            ->shouldBeCalledOnce()
            ->willReturn($dbExam);

        $this->expectException(\RuntimeException::class);

        $this->answerExamHandler->__invoke($answerExam);
    }

    public function testAnswerToNonExistantQuestion(): void
    {
        $answerExam = new AnswerExam(42, 69, $this->getAnswers());

        $dbStudent = new Student();
        $dbStudent->setId(69);

        $dbClassroom = new Classroom();
        $dbClassroom->setStudents([$dbStudent]);

        $dbQuestion = new Question();
        $dbQuestion->setId(1);
        $dbQuestion->setQuestion('Where anakin is born ?');

        $dbExam = new Exam();
        $dbExam->setClassroom($dbClassroom);
        $dbExam->setQuestions([$dbQuestion]);

        $this
            ->examRepository
            ->find($answerExam->getExamId())
            ->shouldBeCalledOnce()
            ->willReturn($dbExam);

        $this->expectException(\RuntimeException::class);

        $this->answerExamHandler->__invoke($answerExam);
    }

    public function testAnswerSuccess(): void
    {
        $answerExam = new AnswerExam(42, 69, $this->getAnswers());

        $dbStudent = new Student();
        $dbStudent->setId(69);

        $dbClassroom = new Classroom();
        $dbClassroom->setStudents([$dbStudent]);

        $dbQuestion = new Question();
        $dbQuestion->setId(10);
        $dbQuestion->setQuestion('Who is Anakin master ?');

        $dbExam = new Exam();
        $dbExam->setClassroom($dbClassroom);
        $dbExam->setQuestions([$dbQuestion]);

        $this
            ->examRepository
            ->find($answerExam->getExamId())
            ->shouldBeCalledOnce()
            ->willReturn($dbExam);

        $this
            ->examRepository
            ->create(Argument::any())
            ->willReturn($dbExam);

        $answerExamWithResult = $this->answerExamHandler->__invoke($answerExam);

        self::assertCount(1, $answerExamWithResult->getSessions());
    }

    private function getAnswers(): array
    {
        return [
            10 => 'obiwan kenoby'
        ];
    }

    public function setUp(): void
    {
        $this->examRepository = $this->prophesize(ExamRepository::class);
        $this->answerExamHandler = new AnswerExamHandler(
            $this->examRepository->reveal(),
            $this->prophesize(EventDispatcherInterface::class)->reveal()
        );
    }
}
