<?php
declare(strict_types=1);

namespace App\Tests\Message\Classroom;

use App\Message\Classroom\UpdateClassroom;
use PHPUnit\Framework\TestCase;

class UpdateClassroomTest extends TestCase
{
    public function testUpdateClassroom()
    {
        $updateClassroom = new UpdateClassroom(
            42,
            "Bespin"
        );

        self::assertEquals("Bespin", $updateClassroom->getName());
        self::assertEquals(42, $updateClassroom->getId());
    }
}
