<?php
declare(strict_types=1);

namespace App\Tests\Message\Classroom;

use App\Message\Classroom\DeleteClassroom;
use PHPUnit\Framework\TestCase;

class DeleteClassroomTest extends TestCase
{
    public function testDeleteClassroom()
    {
        $updateClassroom = new DeleteClassroom(42);

        self::assertEquals(42, $updateClassroom->getId());
    }
}
