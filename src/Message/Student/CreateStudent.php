<?php
declare(strict_types=1);

namespace App\Message\Student;

use Symfony\Component\Validator\Constraints as Assert;

class CreateStudent
{
    /**
     * @Assert\NotBlank
     */
    private string $firstName;

    /**
     * @Assert\NotBlank
     */
    private string $lastName;

    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    private string $email;

    public function __construct(string $firstName, string $lastName, string $email)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }
    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
