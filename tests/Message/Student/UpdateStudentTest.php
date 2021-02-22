<?php
declare(strict_types=1);

namespace App\Tests\Message\Student;

use App\Message\Student\UpdateStudent;
use PHPUnit\Framework\TestCase;

class UpdateStudentTest extends TestCase
{
    public function testUpdateStudent()
    {
        $updateStudent = new UpdateStudent(
            42,
            "anakin",
            "skywalker"
        );

        $this->assertEquals("anakin", $updateStudent->getFirstName());
        $this->assertEquals("skywalker", $updateStudent->getLastName());
        $this->assertEquals(42, $updateStudent->getId());
    }
}
