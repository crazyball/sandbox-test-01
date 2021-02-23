<?php
declare(strict_types=1);

namespace App\MessageHandler\Student;

use App\Entity\Student;
use App\Message\Student\UpdateStudent;
use App\Repository\StudentRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateStudentHandler implements MessageHandlerInterface
{
    private StudentRepository $studentRepository;

    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function __invoke(UpdateStudent $updateStudent): ?Student
    {
        $student = $this->studentRepository->find($updateStudent->getId());

        if (null === $student) {
            throw new \RuntimeException('Student not found');
        }

        if ($updateStudent->getFirstName()) {
            $student->setFirstName($updateStudent->getFirstName());
        }

        if ($updateStudent->getLastName()) {
            $student->setLastName($updateStudent->getLastName());
        }

        $this->studentRepository->updateStudent($student);

        return $student;
    }
}
