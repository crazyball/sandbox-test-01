<?php

namespace App\Tests\MessageHandler\Classroom;

use App\Entity\Classroom;
use App\Message\Classroom\UpdateClassroom;
use App\MessageHandler\Classroom\UpdateClassroomHandler;
use App\Repository\ClassroomRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use RuntimeException;

class UpdateClassroomHandlerTest extends TestCase
{
    use ProphecyTrait;

    private UpdateClassroomHandler $updateClassroomHandler;

    /**
     * @var ClassroomRepository|ObjectProphecy
     */
    private $classroomRepository;

    public function testUpdateNameOfExistingClassroom(): void
    {
        $updateClassroom = new UpdateClassroom(
            42,
            'Bespin'
        );

        $dbClassroom = new Classroom();
        $dbClassroom->setName('Tatooine');

        $this->classroomRepository
            ->find($updateClassroom->getId())
            ->shouldBeCalled()
            ->willReturn($dbClassroom);

        $this->classroomRepository
            ->updateClassroom(Argument::any())
            ->shouldBeCalledOnce();

        $updatedClassroom = $this->updateClassroomHandler->__invoke($updateClassroom);

        self::assertEquals('Bespin', $updatedClassroom->getName());
    }

    public function testUpdateFirstNameOfNotExistingClassroom(): void
    {
        $updateClassroom = new UpdateClassroom(
            42,
            'Tatooine'
        );

        $this->classroomRepository
            ->find($updateClassroom->getId())
            ->shouldBeCalled()
            ->willReturn(null);

        $this->expectException(RuntimeException::class);

        $this->classroomRepository
            ->updateClassroom(Argument::any())
            ->shouldNotBeCalled();

        $this->updateClassroomHandler->__invoke($updateClassroom);
    }

    protected function setUp(): void
    {
        $this->classroomRepository = $this->prophesize(ClassroomRepository::class);
        $this->updateClassroomHandler = new UpdateClassroomHandler($this->classroomRepository->reveal());
    }
}
