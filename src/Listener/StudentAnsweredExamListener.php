<?php
declare(strict_types=1);

namespace App\Listener;

use App\Entity\Exam;
use App\Entity\ExamSession;
use App\Message\Events\StudentAnsweredExamEvent;
use App\Repository\ClassroomRepository;
use App\Repository\ExamRepository;
use App\Repository\StudentRepository;

/**
 * TODO: do not use Listener but asynchronous messaging / queuing to avoid latencies for users
 * TODO: refacto leaderboard calculation scoring as a service just called by this listener
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

        if (count($exam->getQuestions()) != ($exam->getSessions() / count($exam->getClassroom()->getStudents()))) {
            return; // Exam still not finished
        }

        $leaderboard = [];
        foreach ($exam->getSessions() as $session) {
            $score = $this->calculateScore($session);
            $leaderboard[] = $score;
            $session->setScore($score);
        }
        $exam->setAverageScore(array_sum($leaderboard) / count($leaderboard));
        $this->examRepository->save($exam);

        // Calculate Student Average Total Score
        $classroomLeaderboard = [];
        foreach ($exam->getClassroom()->getStudents() as $student) {
            $studentScores = [];
            foreach ($student->getSessions() as $studentSession) {
                $studentScores[] = $studentSession->getScore();
            }
            $averageStudentScore = count($student->getSessions()) === 0 ? 0 : (float) number_format(array_sum($studentScores) / count($student->getSessions()), 2);
            $classroomLeaderboard[] = [
                'student' => $student,
                'score'   => $averageStudentScore
            ];
        }

        $columns = array_column($classroomLeaderboard, 'score');
        array_multisort($columns, SORT_DESC, $classroomLeaderboard);

        foreach ($classroomLeaderboard as $rank => $data) {
            $data['student']->setClassroomRank($rank + 1); // start rank at 1
        }
        $this->examRepository->save($exam);
    }

    private function calculateScore(ExamSession $examSession): float
    {
        $nbValidAnswers = 0;
        foreach ($examSession->getAnswers() as $answer) {
            if ($answer->isValid()) {
                $nbValidAnswers++;
            }
        }

        return $nbValidAnswers == 0 ? 0 : (float) number_format(20 * count($examSession->getExam()->getQuestions()) / $nbValidAnswers, 2);
    }
}
