<?php
declare(strict_types=1);

namespace App\Tests\Message\Student;

use App\Message\Student\DisplayStudent;
use PHPUnit\Framework\TestCase;

class DisplayStudentTest extends TestCase
{
    public function testDisplayStudent()
    {
        $displayStudent = new DisplayStudent(42);

        $this->assertEquals(42, $displayStudent->getId());
    }
}
