<?php
declare(strict_types=1);

namespace App\Tests\MessageHandler\Classroom;

use App\Message\Classroom\CreateClassroom;
use App\MessageHandler\Classroom\CreateClassroomHandler;
use App\Repository\ClassroomRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class CreateClassroomHandlerTest extends TestCase
{
    use ProphecyTrait;

    private CreateClassroomHandler $createClassroomHandler;

    /**
     * @var ClassroomRepository|ObjectProphecy
     */
    private $classroomRepository;

    public function testHandleMessage(): void
    {
        $createClassroom = new CreateClassroom("Tatoine");

        $this->classroomRepository
            ->createClassroom($this->createClassroomHandler->toClassroom($createClassroom))
            ->shouldBeCalled();

        $this->createClassroomHandler->__invoke($createClassroom);
    }

    protected function setUp(): void
    {
        $this->classroomRepository = $this->prophesize(ClassroomRepository::class);
        $this->createClassroomHandler = new CreateClassroomHandler($this->classroomRepository->reveal());
    }
}
