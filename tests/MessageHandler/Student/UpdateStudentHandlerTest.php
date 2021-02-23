<?php

namespace App\Tests\MessageHandler\Student;

use App\Entity\Student;
use App\Message\Student\UpdateStudent;
use App\MessageHandler\Student\UpdateStudentHandler;
use App\Repository\StudentRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use RuntimeException;

class UpdateStudentHandlerTest extends TestCase
{
    use ProphecyTrait;

    private UpdateStudentHandler $updateStudentHandler;

    /**
     * @var StudentRepository|ObjectProphecy
     */
    private $studentRepository;

    public function testUpdateNameOfExistingStudent(): void
    {
        $updateStudent = new UpdateStudent(
            42,
            'obiwan',
            'kenobi'
        );

        $dbStudent = new Student();
        $dbStudent->setFirstName('anakin');
        $dbStudent->setLastName('kenobi');

        $this->studentRepository
            ->find($updateStudent->getId())
            ->shouldBeCalled()
            ->willReturn($dbStudent);

        $this->studentRepository
            ->updateStudent(Argument::any())
            ->shouldBeCalledOnce();

        $updatedStudent = $this->updateStudentHandler->__invoke($updateStudent);

        self::assertEquals('obiwan', $updatedStudent->getFirstName());
        self::assertEquals('kenobi', $updatedStudent->getLastName());
    }

    public function testUpdateFirstNameOfNotExistingStudent(): void
    {
        $updateStudent = new UpdateStudent(
            42,
            'obiwan',
            null
        );

        $this->studentRepository
            ->find($updateStudent->getId())
            ->shouldBeCalled()
            ->willReturn(null);

        $this->expectException(RuntimeException::class);

        $this->studentRepository
            ->updateStudent(Argument::any())
            ->shouldNotBeCalled();

        $this->updateStudentHandler->__invoke($updateStudent);
    }

    protected function setUp(): void
    {
        $this->studentRepository = $this->prophesize(StudentRepository::class);

        $this->updateStudentHandler = new UpdateStudentHandler(
            $this->studentRepository->reveal()
        );
    }
}
