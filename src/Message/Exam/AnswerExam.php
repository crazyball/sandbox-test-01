<?php
declare(strict_types=1);

namespace App\Message\Exam;

class AnswerExam
{
    private int $studentId;

    private int $examId;

    private array $answers;

    public function __construct(int $examId, int $studentId, array $answers)
    {
        $this->examId = $examId;
        $this->studentId = $studentId;
        $this->answers = $answers;
    }

    /**
     * @return int
     */
    public function getStudentId(): int
    {
        return $this->studentId;
    }

    /**
     * @return int
     */
    public function getExamId(): int
    {
        return $this->examId;
    }

    /**
     * @return array
     */
    public function getAnswers(): array
    {
        return $this->answers;
    }
}
