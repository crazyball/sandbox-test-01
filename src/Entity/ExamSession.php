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
     * @return array|iterable
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param array|iterable $answers
     */
    public function setAnswers($answers): void
    {
        $this->answers = $answers;
    }
}
