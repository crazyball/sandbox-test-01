<?php
declare(strict_types=1);

namespace App\MessageHandler\Student;

use App\Entity\Student;
use App\Message\Student\ListStudents;
use App\Repository\StudentRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ListStudentsHandler implements MessageHandlerInterface
{
    private StudentRepository $studentRepository;

    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    /**
     * @param ListStudents $listStudent
     *
     * @return Student[]
     */
    public function __invoke(ListStudents $listStudent): array
    {
        return $this->studentRepository->findAll();
    }
}
