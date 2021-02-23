<?php
declare(strict_types=1);

namespace App\MessageHandler\Classroom;

use App\Entity\Classroom;
use App\Message\Classroom\CreateClassroom;
use App\Repository\ClassroomRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use \RuntimeException;

class CreateClassroomHandler implements MessageHandlerInterface
{
    private ClassroomRepository $classroomRepository;

    public function __construct(ClassroomRepository $classroomRepository)
    {
        $this->classroomRepository = $classroomRepository;
    }

    public function __invoke(CreateClassroom $createClassroom)
    {
        $this->classroomRepository->createClassroom($this->toClassroom($createClassroom));
    }

    public function toClassroom(CreateClassroom $createClassroom): Classroom
    {
        $classroom = new Classroom();
        $classroom->setName($createClassroom->getName());

        return $classroom;
    }
}
