<?php
declare(strict_types=1);

namespace App\Tests\Message\Classroom;

use App\Message\Classroom\ListClassrooms;
use PHPUnit\Framework\TestCase;

class ListClassroomsTest extends TestCase
{
    public function testListClassrooms()
    {
        $listClassrooms = new ListClassrooms();

        self::assertInstanceOf(ListClassrooms::class, $listClassrooms);
    }
}
