<?php
declare(strict_types=1);

namespace App\Tests\MessageHandler\Student;

use App\Entity\Student;
use App\Message\Student\DeleteStudent;
use App\MessageHandler\Student\DeleteStudentHandler;
use App\Repository\StudentRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use RuntimeException;

class DeleteStudentHandlerTest extends TestCase
{
    use ProphecyTrait;

    private DeleteStudentHandler $deleteStudentHandler;

    /**
     * @var StudentRepository|ObjectProphecy
     */
    private $studentRepository;

    public function testHandleMessageExistingStudent(): void
    {
        $deleteStudent = new DeleteStudent(42);

        $this->studentRepository
            ->find($deleteStudent->getId())
            ->shouldBeCalledOnce()
            ->willReturn(new Student());

        $this->studentRepository
            ->deleteStudent(new Student())
            ->shouldBeCalled();

        $this->deleteStudentHandler->__invoke($deleteStudent);
    }

    public function testHandleMessageNotExistingStudent(): void
    {
        $deleteStudent = new DeleteStudent(42);

        $this->studentRepository
            ->find($deleteStudent->getId())
            ->shouldBeCalledOnce()
            ->willReturn(null);

        $this->studentRepository
            ->deleteStudent(Argument::type('integer'))
            ->shouldNotBeCalled();

        $this->expectException(RuntimeException::class);

        $this->deleteStudentHandler->__invoke($deleteStudent);
    }

    protected function setUp(): void
    {
        $this->studentRepository = $this->prophesize(StudentRepository::class);

        $this->deleteStudentHandler = new DeleteStudentHandler(
            $this->studentRepository->reveal()
        );
    }
}
