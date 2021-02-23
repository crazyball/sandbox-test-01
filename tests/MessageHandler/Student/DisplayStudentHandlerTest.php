<?php

namespace App\Tests\MessageHandler\Student;

use App\Entity\Student;
use App\Message\Student\DisplayStudent;
use App\MessageHandler\Student\DisplayStudentHandler;
use App\Repository\StudentRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class DisplayStudentHandlerTest extends TestCase
{
    use ProphecyTrait;

    private DisplayStudentHandler $displayStudentHandler;

    /**
     * @var StudentRepository|ObjectProphecy
     */
    private $studentRepository;

    public function testHandleMessageExistingStudent(): void
    {
        $displayStudent = new DisplayStudent(42);

        $this->studentRepository
            ->find($displayStudent->getId())
            ->shouldBeCalledOnce()
            ->willReturn(new Student());

        $student = $this->displayStudentHandler->__invoke($displayStudent);

        self::assertInstanceOf(Student::class, $student);
    }

    public function testHandleMessageNotExistingStudent(): void
    {
        $displayStudent = new DisplayStudent(42);

        $this->studentRepository
            ->find($displayStudent->getId())
            ->shouldBeCalledOnce()
            ->willReturn(null);

        $student = $this->displayStudentHandler->__invoke($displayStudent);

        self::assertNull($student);
    }

    protected function setUp(): void
    {
        $this->studentRepository = $this->prophesize(StudentRepository::class);

        $this->displayStudentHandler = new DisplayStudentHandler(
            $this->studentRepository->reveal()
        );
    }
}
