<?php
declare(strict_types=1);

namespace App\Tests\Message\Exam;

use App\Message\Exam\CreateExam;
use PHPUnit\Framework\TestCase;

class CreateExamTest extends TestCase
{
    public function testCreateExam()
    {
        $createExam = new CreateExam(42);

        self::assertEquals(42, $createExam->getClassroom());
    }
}
