<?php
declare(strict_types=1);

namespace App\MessageHandler\Student;

use App\Message\Student\DeleteStudent;
use App\Repository\StudentRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeleteStudentHandler implements MessageHandlerInterface
{
    private StudentRepository $studentRepository;

    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function __invoke(DeleteStudent $displayStudent): void
    {
        $student = $this->studentRepository->find($displayStudent->getId());

        if (null === $student) {
            throw new \RuntimeException('Student not found');
        }

        $this->studentRepository->deleteStudent($student);
    }
}
