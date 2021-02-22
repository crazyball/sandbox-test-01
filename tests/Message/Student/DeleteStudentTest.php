<?php
declare(strict_types=1);

namespace App\Tests\Message\Student;

use App\Message\Student\DeleteStudent;
use PHPUnit\Framework\TestCase;

class DeleteStudentTest extends TestCase
{
    public function testDeleteStudent()
    {
        $updateStudent = new DeleteStudent(42);

        $this->assertEquals(42, $updateStudent->getId());
    }
}
