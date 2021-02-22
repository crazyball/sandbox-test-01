<?php
declare(strict_types=1);

namespace App\Message\Student;

class UpdateStudent
{
    private int $id;

    private ?string $firstName;

    private ?string $lastName;

    public function __construct(int $id, ?string $firstName, ?string $lastName)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }
}
