<?php
declare(strict_types=1);

namespace App\Tests\Message\Student;

use App\Message\Student\CreateStudent;
use PHPUnit\Framework\TestCase;

class CreateStudentTest extends TestCase
{
    public function testCreateStudent()
    {
        $createStudent = new CreateStudent(
            "anakin",
            "skywalker",
            "anakin@darkside.com"
        );

        $this->assertEquals("anakin", $createStudent->getFirstName());
        $this->assertEquals("skywalker", $createStudent->getLastName());
        $this->assertEquals("anakin@darkside.com", $createStudent->getEmail());
    }
}
