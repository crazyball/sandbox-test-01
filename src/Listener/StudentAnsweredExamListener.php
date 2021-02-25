<?php
declare(strict_types=1);

namespace App\Listener;

use App\Message\Events\StudentAnsweredExamEvent;
use App\Repository\ExamRepository;

/**
 * TODO: do not use Listener but asynchronous messaging / queuing to avoid latencies for users
 */
class StudentAnsweredExamListener
{
    protected ExamRepository $examRepository;

    public function __construct(ExamRepository $examRepository)
    {
        $this->examRepository = $examRepository;
    }

    public function onStudentAnsweredExam(StudentAnsweredExamEvent $event)
    {
        $exam = $this->examRepository->find($event->getId());

        foreach ($exam->getSessions() as $session) {
            // TODO Calculate stats
            // TODO Store stats
        }
    }
}
