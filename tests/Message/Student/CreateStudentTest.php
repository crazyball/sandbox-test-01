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

        self::assertEquals("anakin", $createStudent->getFirstName());
        self::assertEquals("skywalker", $createStudent->getLastName());
        self::assertEquals("anakin@darkside.com", $createStudent->getEmail());
    }
}
