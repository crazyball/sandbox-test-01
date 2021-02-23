<?php
declare(strict_types=1);

namespace App\Tests\MessageHandler\Classroom;

use App\Entity\Classroom;
use App\Message\Classroom\DeleteClassroom;
use App\MessageHandler\Classroom\DeleteClassroomHandler;
use App\Repository\ClassroomRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use RuntimeException;

class DeleteClassroomHandlerTest extends TestCase
{
    use ProphecyTrait;

    private DeleteClassroomHandler $deleteClassroomHandler;

    /**
     * @var ClassroomRepository|ObjectProphecy
     */
    private $classroomRepository;

    public function testHandleMessageExistingClassroom(): void
    {
        $deleteClassroom = new DeleteClassroom(42);

        $this->classroomRepository
            ->find($deleteClassroom->getId())
            ->shouldBeCalledOnce()
            ->willReturn(new Classroom());

        $this->classroomRepository
            ->deleteClassroom(new Classroom())
            ->shouldBeCalled();

        $this->deleteClassroomHandler->__invoke($deleteClassroom);
    }

    public function testHandleMessageNotExistingClassroom(): void
    {
        $deleteClassroom = new DeleteClassroom(42);

        $this->classroomRepository
            ->find($deleteClassroom->getId())
            ->shouldBeCalledOnce()
            ->willReturn(null);

        $this->classroomRepository
            ->deleteClassroom(Argument::type('integer'))
            ->shouldNotBeCalled();

        $this->expectException(RuntimeException::class);

        $this->deleteClassroomHandler->__invoke($deleteClassroom);
    }

    protected function setUp(): void
    {
        $this->classroomRepository = $this->prophesize(ClassroomRepository::class);
        $this->deleteClassroomHandler = new DeleteClassroomHandler($this->classroomRepository->reveal());
    }
}
