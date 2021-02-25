<?php
declare(strict_types=1);

namespace App\Tests\MessageHandler\Exam;

use App\Entity\Exam;
use App\Message\Exam\ShowExam;
use App\MessageHandler\Exam\ShowExamHandler;
use App\Repository\ExamRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class ShowExamHandlerTest extends TestCase
{
    use ProphecyTrait;

    private $examRepository;
    private ShowExamHandler $showExamHandler;

    public function testShowExistingExam():void
    {
        $showExam = new ShowExam(42);

        $this->examRepository
            ->find(42)
            ->shouldBeCalledOnce()
            ->willReturn(new Exam());

        $exam = $this->showExamHandler->__invoke($showExam);

        self::assertInstanceOf(Exam::class, $exam);
    }

    public function testShowNonExistingExam(): void
    {
        $showExam = new ShowExam(42);

        $this->examRepository
            ->find(42)
            ->shouldBeCalledOnce()
            ->willReturn(null);

        $exam = $this->showExamHandler->__invoke($showExam);

        self::assertNull($exam);
    }

    protected function setUp(): void
    {
        $this->examRepository = $this->prophesize(ExamRepository::class);

        $this->showExamHandler = new ShowExamHandler($this->examRepository->reveal());
    }
}
