<?php

namespace App\Tests\MessageHandler\Student;

use App\Entity\Student;
use App\Message\Student\DisplayStudent;
use App\Message\Student\ListStudents;
use App\MessageHandler\Student\DisplayStudentHandler;
use App\MessageHandler\Student\ListStudentsHandler;
use App\Repository\StudentRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class ListStudentsHandlerTest extends TestCase
{
    use ProphecyTrait;

    private ListStudentsHandler $listStudentsHandler;

    /**
     * @var StudentRepository|ObjectProphecy
     */
    private $studentRepository;

    public function testHandleMessageExistingStudent(): void
    {
        $listStudents = new ListStudents();

        $this->studentRepository
            ->findAll()
            ->shouldBeCalledOnce()
            ->willReturn([new Student(), new Student()]);

        $students = $this->listStudentsHandler->__invoke($listStudents);

        self::assertCount(2, $students);
    }

    protected function setUp(): void
    {
        $this->studentRepository = $this->prophesize(StudentRepository::class);
        $this->listStudentsHandler = new ListStudentsHandler($this->studentRepository->reveal());
    }
}
