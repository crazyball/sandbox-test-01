<?php
declare(strict_types=1);

namespace App\Message\Exam;

class CreateExam
{
    private int $classroom;

    public function __construct(int $classroom)
    {
        $this->classroom = $classroom;
    }

    /**
     * @return int
     */
    public function getClassroom(): int
    {
        return $this->classroom;
    }
}
