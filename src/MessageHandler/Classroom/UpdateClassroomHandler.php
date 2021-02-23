<?php
declare(strict_types=1);

namespace App\MessageHandler\Classroom;

use App\Entity\Classroom;
use App\Message\Classroom\UpdateClassroom;
use App\Repository\ClassroomRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateClassroomHandler implements MessageHandlerInterface
{
    private ClassroomRepository $classroomRepository;

    public function __construct(ClassroomRepository $classroomRepository)
    {
        $this->classroomRepository = $classroomRepository;
    }

    public function __invoke(UpdateClassroom $updateClassroom): ?Classroom
    {
        $classroom = $this->classroomRepository->find($updateClassroom->getId());

        if (null === $classroom) {
            throw new \RuntimeException('Classroom not found');
        }

        if($updateClassroom->getName()) {
            $classroom->setName($updateClassroom->getName());
        }

        $this->classroomRepository->updateClassroom($classroom);

        return $classroom;
    }
}
