<?php
declare(strict_types=1);

namespace App\Tests\MessageHandler\Student;

use App\Entity\Classroom;
use App\Message\Student\CreateStudent;
use App\MessageHandler\Student\CreateStudentHandler;
use App\Repository\ClassroomRepository;
use App\Repository\StudentRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use \RuntimeException;

class CreateStudentHandlerTest extends TestCase
{
    use ProphecyTrait;

    private CreateStudentHandler $createStudentHandler;

    /**
     * @var ClassroomRepository|ObjectProphecy
     */
    private $classroomRepository;

    /**
     * @var StudentRepository|ObjectProphecy
     */
    private $studentRepository;

    protected function setUp(): void
    {
        $this->classroomRepository = $this->prophesize(ClassroomRepository::class);
        $this->studentRepository = $this->prophesize(StudentRepository::class);

        $this->createStudentHandler = new CreateStudentHandler(
          $this->classroomRepository->reveal(),
          $this->studentRepository->reveal()
        );
    }

    public function testHandleMessageWithClassroomAvailable(): void
    {
        $createStudent = new CreateStudent(
            "anakin",
            "skywalker",
            "anakin@darkside.com"
        );

        $this->classroomRepository
            ->findWithDisponibilities()
            ->shouldBeCalledOnce()
            ->willReturn([new Classroom()]);

        $this->studentRepository
            ->createStudent(
                $this->createStudentHandler->toStudent($createStudent),
                new Classroom()
            )
            ->shouldBeCalled();

        $this->createStudentHandler->__invoke($createStudent);
    }

    public function testHandleMessageWithNoClassroomAvailable(): void
    {
        $createStudent = new CreateStudent(
            "anakin",
            "skywalker",
            "anakin@darkside.com"
        );

        $this->classroomRepository
            ->findWithDisponibilities()
            ->shouldBeCalledOnce()
            ->willReturn([]);

        $this->studentRepository
            ->createStudent(Argument::any(), Argument::any())
            ->shouldNotBeCalled();

        $this->expectException(RuntimeException::class);

        $this->createStudentHandler->__invoke($createStudent);
    }
}
