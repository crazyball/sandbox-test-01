<?php
declare(strict_types=1);

namespace App\MessageHandler\Student;

use App\Entity\Student;
use App\Message\Student\DisplayStudent;
use App\Repository\StudentRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DisplayStudentHandler implements MessageHandlerInterface
{
    private StudentRepository $studentRepository;

    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function __invoke(DisplayStudent $displayStudent): ?Student
    {
        return $this->studentRepository->find($displayStudent->getId());
    }
}
