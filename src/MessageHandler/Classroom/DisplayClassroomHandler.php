<?php
declare(strict_types=1);

namespace App\MessageHandler\Classroom;

use App\Entity\Classroom;
use App\Message\Classroom\DisplayClassroom;
use App\Repository\ClassroomRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DisplayClassroomHandler implements MessageHandlerInterface
{
    private ClassroomRepository $classroomRepository;

    public function __construct(ClassroomRepository $classroomRepository)
    {
        $this->classroomRepository = $classroomRepository;
    }

    public function __invoke(DisplayClassroom $displayClassroom): ?Classroom
    {
        return $this->classroomRepository->find($displayClassroom->getId());
    }
}
