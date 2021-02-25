<?php

namespace App\Tests\Message\Student;

use App\Message\Student\ShowStudentExam;
use PHPUnit\Framework\TestCase;

class ShowStudentExamTest extends TestCase
{
    public function testDisplayStudent()
    {
        $showStudentExam = new ShowStudentExam(42);

        self::assertEquals(42, $showStudentExam->getId());
    }
}
