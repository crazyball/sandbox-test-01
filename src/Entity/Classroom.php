<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\ClassroomRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ClassroomRepository::class)
 */
class Classroom
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank
     */
    private ?string $name;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Professor", mappedBy="classroom")
     */
    private ?Professor $professor;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Student", mappedBy="classroom")
     * @Assert\Count(
     *      max = 30,
     *      maxMessage = "You can add more than {{ limit }} students to each classrooms"
     * )
     */
    private iterable $students;

    public function __construct()
    {
        $this->students = [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return ?Professor
     */
    public function getProfessor(): ?Professor
    {
        return $this->professor;
    }

    /**
     * @param Professor $professor
     */
    public function setProfessor(Professor $professor): void
    {
        $this->professor = $professor;
    }

    /**
     * @return \App\Entity\Student[]
     */
    public function getStudents(): iterable
    {
        return $this->students;
    }

    /**
     * @param \App\Entity\Student[] $students
     */
    public function setStudents(iterable $students): void
    {
        $this->students = $students;
    }
}
