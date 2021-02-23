<?php
declare(strict_types=1);

namespace App\MessageHandler\Classroom;

use App\Entity\Classroom;
use App\Message\Classroom\ListClassrooms;
use App\Repository\ClassroomRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ListClassroomsHandler implements MessageHandlerInterface
{
    private ClassroomRepository $classroomRepository;

    public function __construct(ClassroomRepository $classroomRepository)
    {
        $this->classroomRepository = $classroomRepository;
    }

    /**
     * @param ListClassrooms $listClassroom
     *
     * @return Classroom[]
     */
    public function __invoke(ListClassrooms $listClassroom): array
    {
        return $this->classroomRepository->findAll();
    }
}
