<?php
declare(strict_types=1);

namespace App\Tests\Message\Classroom;

use App\Message\Classroom\DisplayClassroom;
use PHPUnit\Framework\TestCase;

class DisplayClassroomTest extends TestCase
{
    public function testDisplayClassroom()
    {
        $displayClassroom = new DisplayClassroom(42);

        self::assertEquals(42, $displayClassroom->getId());
    }
}
