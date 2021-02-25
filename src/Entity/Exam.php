<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\ExamRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ExamRepository::class)
 */
class Exam
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Classroom")
     */
    private Classroom $classroom;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Question", cascade={"persist"})
     * @Assert\Count(
     *      min = 3,
     *      max = 5,
     *      minMessage = "You can add less than {{ limit }} questions to each test",
     *      maxMessage = "You can add more than {{ limit }} questions to each test"
     * )
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExamSession", mappedBy="exam", cascade={"persist"})
     */
    private $sessions;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $averageScore;

    public function __construct()
    {
        $this->questions = [];
        $this->sessions = [];
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
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

    /**
     * @return iterable|Question[]
     */
    public function getQuestions(): iterable
    {
        return $this->questions;
    }

    /**
     * @param iterable|Question[] $questions
     */
    public function setQuestions(iterable $questions): void
    {
        $this->questions = $questions;
    }

    /**
     * @return iterable|ExamSession[]
     */
    public function getSessions(): iterable
    {
        return $this->sessions;
    }

    /**
     * @param ExamSession $examSession
     */
    public function addSession(ExamSession $examSession): void
    {
        $this->sessions[] = $examSession;
    }

    /**
     * @param iterable|ExamSession[] $sessions
     */
    public function setSessions(iterable $sessions): void
    {
        $this->sessions = $sessions;
    }

    /**
     * @return mixed
     */
    public function getAverageScore()
    {
        return $this->averageScore;
    }

    /**
     * @param mixed $averageScore
     */
    public function setAverageScore($averageScore): void
    {
        $this->averageScore = $averageScore;
    }
}
