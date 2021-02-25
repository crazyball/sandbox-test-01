<?php
declare(strict_types=1);

namespace App\Tests\MessageHandler\Exam;

use App\Entity\Classroom;
use App\Entity\Question;
use App\Message\Exam\CreateExam;
use App\MessageHandler\Exam\CreateExamHandler;
use App\Repository\ClassroomRepository;
use App\Repository\ExamRepository;
use App\Repository\QuestionRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class CreateExamHandlerTest extends TestCase
{
    use ProphecyTrait;

    private CreateExamHandler $createExamHandler;

    private $classroomRepository;
    private $examRepository;
    private $questionRepository;


    public function testCreateExamOnAlreadyOpenedExam(): void
    {
        $createExam = new CreateExam(42);

        $this->classroomRepository
            ->haveCurrentExamOpened(42)
            ->shouldBeCalledOnce()
            ->willReturn(true);

        $this->expectException(\RuntimeException::class);

        $this->createExamHandler->__invoke($createExam);
    }

    public function testCreateExamOnNonExistingClassroom(): void
    {
        $createExam = new CreateExam(42);

        $this->classroomRepository
            ->haveCurrentExamOpened(42)
            ->shouldBeCalledOnce()
            ->willReturn(false);

        $this->classroomRepository
            ->find(42)
            ->shouldBeCalledOnce()
            ->willReturn(null);

        $this->expectException(\RuntimeException::class);

        $this->createExamHandler->__invoke($createExam);
    }

    public function testCreateExamSuccess(): void
    {
        $createExam = new CreateExam(42);

        $this->classroomRepository
            ->haveCurrentExamOpened(42)
            ->shouldBeCalledOnce()
            ->willReturn(false);

        $this->classroomRepository
            ->find(42)
            ->shouldBeCalledOnce()
            ->willReturn(new Classroom());

        $this->questionRepository
            ->getRandomQuestions()
            ->shouldBeCalledOnce()
            ->willReturn([new Question(), new Question(), new Question()]);

        $exam = $this->createExamHandler->__invoke($createExam);

        self::assertCount(3, $exam->getQuestions());
    }

    protected function setUp(): void
    {
        $this->classroomRepository = $this->prophesize(ClassroomRepository::class);
        $this->examRepository = $this->prophesize(ExamRepository::class);
        $this->questionRepository = $this->prophesize(QuestionRepository::class);

        $this->createExamHandler = new CreateExamHandler(
            $this->examRepository->reveal(),
            $this->classroomRepository->reveal(),
            $this->questionRepository->reveal()
        );
    }
}
