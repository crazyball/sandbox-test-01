<?php

namespace App\Tests\MessageHandler\Student;

use App\Entity\Exam;
use App\Entity\Student;
use App\Message\Student\DisplayStudent;
use App\Message\Student\ShowStudentExam;
use App\MessageHandler\Student\ShowStudentExamHandler;
use App\Repository\ExamRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class ShowStudentExamHandlerTest extends TestCase
{
    use ProphecyTrait;

    private ShowStudentExamHandler $showStudentExamHandler;

    /**
     * @var ExamRepository|ObjectProphecy
     */
    private $examRepository;

    public function testShowExistingExam(): void
    {
        $showStudentExam = new ShowStudentExam(42);

        $this->examRepository
            ->findExamForStudent($showStudentExam->getId())
            ->shouldBeCalledOnce()
            ->willReturn(new Exam());

        $exam = $this->showStudentExamHandler->__invoke($showStudentExam);

        self::assertInstanceOf(Exam::class, $exam);
    }

    public function testShowNonExistingExam(): void
    {
        $showStudentExam = new ShowStudentExam(42);

        $this->examRepository
            ->findExamForStudent($showStudentExam->getId())
            ->shouldBeCalledOnce()
            ->willReturn(null);

        $exam = $this->showStudentExamHandler->__invoke($showStudentExam);

        self::assertNull($exam);
    }

    protected function setUp(): void
    {
        $this->examRepository = $this->prophesize(ExamRepository::class);

        $this->showStudentExamHandler = new ShowStudentExamHandler(
            $this->examRepository->reveal()
        );
    }
}
