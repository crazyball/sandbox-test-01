<?php
declare(strict_types=1);

namespace App\Listener;

use App\Message\Events\StudentAnsweredExamEvent;
use App\Repository\ExamRepository;

class StudentAnsweredExamListener
{
    protected ExamRepository $examRepository;

    public function __construct(ExamRepository $examRepository)
    {
        $this->examRepository = $examRepository;
    }

    public function onStudentAnsweredExam(StudentAnsweredExamEvent $event): void
    {
        $exam = $this->examRepository->find($event->getId());

        foreach ($exam->getSessions() as $session) {
            // TODO Calculate stats
            // Store stats
        }
    }
}
