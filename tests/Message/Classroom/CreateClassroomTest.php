<?php
declare(strict_types=1);

namespace App\Tests\Message\Classroom;

use App\Message\Classroom\CreateClassroom;
use PHPUnit\Framework\TestCase;

class CreateClassroomTest extends TestCase
{
    public function testCreateClassroom()
    {
        $createClassroom = new CreateClassroom('Tatooine');

        self::assertEquals("Tatooine", $createClassroom->getName());
    }
}
