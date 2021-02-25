<?php
declare(strict_types=1);

namespace App\Message\Events;

use Symfony\Contracts\EventDispatcher\Event;

class StudentAnsweredExamEvent extends Event
{
    const NAME = 'student.answered_exam';

    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
