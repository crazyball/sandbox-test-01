<?php
declare(strict_types=1);

namespace App\MessageHandler\Exam;

use App\Entity\Exam;
use App\Entity\ExamSession;
use App\Entity\Question;
use App\Entity\Student;
use App\Message\Events\StudentAnsweredExamEvent;
use App\Message\Exam\AnswerExam;
use App\Repository\ExamRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
        // TODO : check that session is not already answered

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
            $examSession->setIsValid($question->getAnswer() === $answer);
            $examSessions[] = $examSession;
        }
        $exam->setSessions($examSessions);
        //$this->examRepository->create($exam);

        // Dispatch Event to stats calculations (student note, exam note etc.)
        $this->eventDispatcher->dispatch(new StudentAnsweredExamEvent($exam->getId()), StudentAnsweredExamEvent::NAME);

        return $exam;
    }

    private function matchStudent(Exam $exam, $studentId): ?Student
    {
        foreach ($exam->getClassroom()->getStudents() as $student) {
            if ($student->getId() == $studentId) {
                return $student;
            }
        }

        return null;
    }

    private function matchQuestion(Exam $exam, $questionId): ?Question
    {
        foreach ($exam->getQuestions() as $question) {
            if ($question->getId() == $questionId) {
                return $question;
            }
        }

        return null;
    }
}
