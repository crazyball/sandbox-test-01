<?php
declare(strict_types=1);

namespace App\Tests\Message\Student;

use App\Message\Student\ListStudents;
use PHPUnit\Framework\TestCase;

class ListStudentsTest extends TestCase
{
    public function testListStudents()
    {
        $listStudents = new ListStudents();

        self::assertInstanceOf(ListStudents::class, $listStudents);
    }
}
