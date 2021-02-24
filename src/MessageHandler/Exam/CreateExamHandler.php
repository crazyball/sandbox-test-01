<?php
declare(strict_types=1);

namespace App\MessageHandler\Exam;

use App\Entity\Exam;
use App\Message\Exam\CreateExam;
use App\Repository\ClassroomRepository;
use App\Repository\ExamRepository;
use App\Repository\QuestionRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateExamHandler implements MessageHandlerInterface
{
    private ExamRepository $examRepository;

    private ClassroomRepository $classroomRepository;

    private QuestionRepository $questionRepository;

    public function __construct(
        ExamRepository $examRepository,
        ClassroomRepository $classroomRepository,
        QuestionRepository $questionRepository
    ) {
        $this->examRepository = $examRepository;
        $this->classroomRepository = $classroomRepository;
        $this->questionRepository = $questionRepository;
    }

    public function __invoke(CreateExam $createExam): Exam
    {
        $hasExam = $this->classroomRepository->haveCurrentExamOpened($createExam->getClassroom());
        if ($hasExam) {
            throw new \RuntimeException('Classroom already have an exam in progress, can\'t generate new exam.');
        }

        $exam = $this->createExam($createExam->getClassroom());

        $this->examRepository->create($exam);

        return $exam;
    }

    private function createExam(int $classroomId): Exam
    {
        $classroom = $this->classroomRepository->find($classroomId);
        if (!$classroom) {
            throw new \RuntimeException('Classroom not found');
        }

        $questions = $this->questionRepository->getRandomQuestions();

        $exam = new Exam();
        $exam->setClassroom($classroom);
        $exam->setQuestions($questions);

        return $exam;
    }
}
