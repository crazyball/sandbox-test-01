<?php
declare(strict_types=1);

namespace App\MessageHandler\Student;

use App\Entity\Student;
use App\Message\Student\CreateStudent;
use App\Repository\ClassroomRepository;
use App\Repository\StudentRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use \RuntimeException;

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
            throw new RuntimeException('No classroom available.');
        }

        $this->studentRepository
            ->createStudent(
                $this->toStudent($createStudent),
                $classRooms[0]
            );
    }

    public function toStudent(CreateStudent $createStudent): Student
    {
        $student = new Student();
        $student->setFirstName($createStudent->getFirstName());
        $student->setLastName($createStudent->getLastName());
        $student->setEmail($createStudent->getEmail());

        return $student;
    }
}
