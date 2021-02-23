<?php

namespace App\Tests\MessageHandler\Classroom;

use App\Entity\Classroom;
use App\Message\Classroom\DisplayClassroom;
use App\MessageHandler\Classroom\DisplayClassroomHandler;
use App\Repository\ClassroomRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class DisplayClassroomHandlerTest extends TestCase
{
    use ProphecyTrait;

    private DisplayClassroomHandler $displayClassroomHandler;

    /**
     * @var ClassroomRepository|ObjectProphecy
     */
    private $classroomRepository;

    public function testHandleMessageExistingClassroom(): void
    {
        $displayClassroom = new DisplayClassroom(42);

        $this->classroomRepository
            ->find($displayClassroom->getId())
            ->shouldBeCalledOnce()
            ->willReturn(new Classroom());

        $classroom = $this->displayClassroomHandler->__invoke($displayClassroom);

        self::assertInstanceOf(Classroom::class, $classroom);
    }

    public function testHandleMessageNotExistingClassroom(): void
    {
        $displayClassroom = new DisplayClassroom(42);

        $this->classroomRepository
            ->find($displayClassroom->getId())
            ->shouldBeCalledOnce()
            ->willReturn(null);

        $classroom = $this->displayClassroomHandler->__invoke($displayClassroom);

        self::assertNull($classroom);
    }

    protected function setUp(): void
    {
        $this->classroomRepository = $this->prophesize(ClassroomRepository::class);
        $this->displayClassroomHandler = new DisplayClassroomHandler($this->classroomRepository->reveal());
    }
}
