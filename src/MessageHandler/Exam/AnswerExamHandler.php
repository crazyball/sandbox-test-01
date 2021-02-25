<?php
declare(strict_types=1);

namespace App\MessageHandler\Exam;

use App\Entity\Exam;
use App\Entity\ExamSession;
use App\Entity\Question;
use App\Entity\Student;
use App\Message\Events\StudentAnsweredEvent;
use App\Message\Exam\AnswerExam;
use App\Repository\ExamRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AnswerExamHandler implements MessageHandlerInterface
{
    private ExamRepository $examRepository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        ExamRepository $examRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->examRepository = $examRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(AnswerExam $answerExam): Exam
    {
        $exam = $this->examRepository->find($answerExam->getExamId());
        if (null === $exam) {
            throw new \RuntimeException('Exam not found');
        }

        $student = $this->matchStudent($exam, $answerExam->getStudentId());
        if (null === $student) {
            throw new \RuntimeException('Exam not associated to this student');
        }

        $examSessions = [];
        foreach ($answerExam->getAnswers() as $questionId => $answer) {
            $examSession = new ExamSession();
            $examSession->setExam($exam);
            $question = $this->matchQuestion($exam, $questionId);
            if (null === $question) {
                throw new \RuntimeException('Question to answer not found');
            }
            $examSession->setQuestion($question);
            $examSession->setAnswer($answer);
            $examSessions[] = $examSession;
        }
        $exam->setSessions($examSessions);
        $this->examRepository->create($exam);

        // Dispatch Event to stats calculations (student note, exam note etc.)
        $this->eventDispatcher->dispatch(new StudentAnsweredEvent());

        return $exam;
    }

    private function matchStudent(Exam $exam, $studentId): ?Student
    {
        $student = array_filter(
            $exam->getClassroom()->getStudents(),
            function (Student $e) use (&$studentId) {
                return $e->getId() == $studentId;
            }
        );

        return count($student) > 0 ? $student[0] : null;
    }

    private function matchQuestion(Exam $exam, $questionId): ?Question
    {
        $question = array_filter(
            $exam->getQuestions(),
            function (Question $e) use (&$questionId) {
                return $e->getId() == $questionId;
            }
        );

        return count($question) > 0 ? $question[0] : null;
    }
}
