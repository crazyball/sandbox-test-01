<?php
declare(strict_types=1);

namespace App\MessageHandler\Classroom;

use App\Message\Classroom\DeleteClassroom;
use App\Repository\ClassroomRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeleteClassroomHandler implements MessageHandlerInterface
{
    private ClassroomRepository $classroomRepository;

    public function __construct(ClassroomRepository $classroomRepository)
    {
        $this->classroomRepository = $classroomRepository;
    }

    public function __invoke(DeleteClassroom $deleteClassroom): void
    {
        $classroom = $this->classroomRepository->find($deleteClassroom->getId());

        if (null === $classroom) {
            throw new \RuntimeException('Classroom not found');
        }

        $this->classroomRepository->deleteClassroom($classroom);
    }
}
