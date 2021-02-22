<?php
declare(strict_types=1);

namespace App\MessageHandler\Student;

use App\Message\Student\CreateStudent;
use App\Repository\ClassroomRepository;
use App\Repository\StudentRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateStudentHandler implements MessageHandlerInterface
{
    private ClassroomRepository $classroomRepository;
    private StudentRepository $studentRepository;

    public function __construct(ClassroomRepository $classroomRepository, StudentRepository $studentRepository)
    {
        $this->classroomRepository = $classroomRepository;
        $this->studentRepository = $studentRepository;
    }

    public function __invoke(CreateStudent $createStudent)
    {
        $classRooms = $this->classroomRepository->findWithDisponibilities();
        if(empty($classRooms)) {
            throw new NotFoundHttpException('No classroom available.');
        }

        $this->studentRepository->createStudent($createStudent->getFirstName(), $createStudent->getLastName(), $createStudent->getEmail(), $classRooms[0]);
    }
}
