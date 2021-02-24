<?php
declare(strict_types=1);

namespace App\MessageHandler\Exam;

use App\Entity\Exam;
use App\Message\Exam\ShowExam;
use App\Repository\ExamRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ShowExamHandler implements MessageHandlerInterface
{
    private ExamRepository $examRepository;

    public function __construct(ExamRepository $examRepository)
    {
        $this->examRepository = $examRepository;
    }

    public function __invoke(ShowExam $showExam): Exam
    {
        return $this->examRepository->find($showExam->getId());
    }
}
