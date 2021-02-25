<?php
declare(strict_types=1);

namespace App\Tests\Message\Exam;

use App\Message\Exam\ShowExam;
use PHPUnit\Framework\TestCase;

class ShowExamTest extends TestCase
{
    public function testCreateExam()
    {
        $showExam = new ShowExam(42);

        self::assertEquals(42, $showExam->getId());
    }
}
