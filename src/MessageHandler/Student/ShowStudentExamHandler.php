<?php
declare(strict_types=1);

namespace App\MessageHandler\Student;

use App\Entity\Exam;
use App\Message\Student\ShowStudentExam;
use App\Repository\ExamRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ShowStudentExamHandler implements MessageHandlerInterface
{
    private ExamRepository $examRepository;

    public function __construct(ExamRepository $examRepository)
    {
        $this->examRepository = $examRepository;
    }

    public function __invoke(ShowStudentExam $showStudentExam): ?Exam
    {
        return $this->examRepository->findExamForStudent($showStudentExam->getId());
    }
}
