<?php

namespace App\Tests\MessageHandler\Classroom;

use App\Entity\Classroom;
use App\Message\Classroom\DisplayClassroom;
use App\Message\Classroom\ListClassrooms;
use App\MessageHandler\Classroom\DisplayClassroomHandler;
use App\MessageHandler\Classroom\ListClassroomsHandler;
use App\Repository\ClassroomRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class ListClassroomsHandlerTest extends TestCase
{
    use ProphecyTrait;

    private ListClassroomsHandler $listClassroomsHandler;

    /**
     * @var ClassroomRepository|ObjectProphecy
     */
    private $classroomRepository;

    public function testHandleMessageExistingClassroom(): void
    {
        $listClassrooms = new ListClassrooms();

        $this->classroomRepository
            ->findAll()
            ->shouldBeCalledOnce()
            ->willReturn([new Classroom(), new Classroom()]);

        $classrooms = $this->listClassroomsHandler->__invoke($listClassrooms);

        self::assertCount(2, $classrooms);
    }

    protected function setUp(): void
    {
        $this->classroomRepository = $this->prophesize(ClassroomRepository::class);
        $this->listClassroomsHandler = new ListClassroomsHandler($this->classroomRepository->reveal());
    }
}
