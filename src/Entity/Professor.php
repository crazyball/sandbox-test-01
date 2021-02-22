<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProfessorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfessorRepository::class)
 */
class Professor
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private ?string $firstName;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private ?string $lastName;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Classroom", inversedBy="students")
     */
    private Classroom $classroom;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Classroom
     */
    public function getClassroom(): Classroom
    {
        return $this->classroom;
    }

    /**
     * @param Classroom $classroom
     */
    public function setClassroom(Classroom $classroom): void
    {
        $this->classroom = $classroom;
    }
}
