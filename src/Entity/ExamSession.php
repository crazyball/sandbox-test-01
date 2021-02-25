<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\ExamSessionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExamSessionRepository::class)
 */
class ExamSession
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Exam", inversedBy="questions", cascade={"persist"})
     */
    private Exam $exam;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Student", cascade={"persist"})
     */
    private Student $student;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExamSessionAnswer", mappedBy="examSession", cascade={"persist"})
     */
    private iterable $answers;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private float $score;

    public function __construct()
    {
        $this->answers = [];
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Exam
     */
    public function getExam(): Exam
    {
        return $this->exam;
    }

    /**
     * @param Exam $exam
     */
    public function setExam(Exam $exam): void
    {
        $this->exam = $exam;
    }

    /**
     * @return Student
     */
    public function getStudent(): Student
    {
        return $this->student;
    }

    /**
     * @param Student $student
     */
    public function setStudent(Student $student): void
    {
        $this->student = $student;
    }

    /**
     * @return array|iterable|ExamSessionAnswer[]
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param array|iterable|ExamSessionAnswer[] $answers
     */
    public function setAnswers($answers): void
    {
        $this->answers = $answers;
    }

    /**
     * @return float
     */
    public function getScore(): float
    {
        return $this->score;
    }

    /**
     * @param float $score
     */
    public function setScore(float $score): void
    {
        $this->score = $score;
    }
}
