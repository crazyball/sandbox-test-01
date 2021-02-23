<?php
declare(strict_types=1);

namespace App\Message\Classroom;

use Symfony\Component\Validator\Constraints as Assert;

class CreateClassroom
{
    /**
     * @Assert\NotBlank
     */
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
