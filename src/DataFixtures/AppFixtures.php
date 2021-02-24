<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Classroom;
use App\Entity\Exam;
use App\Entity\Professor;
use App\Entity\Question;
use App\Entity\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;
    private ObjectManager $manager;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->faker = Factory::create();

        // Structure Classes
        $classrooms = [];
        for ($i = 0; $i < 10; $i++) {
            $classroom = new Classroom();
            $classroom->setName("class " . $i);
            $classrooms[] = $classroom;
            $this->manager->persist($classroom);
        }
        $manager->flush();

        foreach ($classrooms as $classroom) {
            $this->createProfessor($classroom);
            $this->createStudents($classroom);
        }
        $manager->flush();

        // Structure Tests
        $exam = new Exam();
        $exam->setClassroom($classrooms[0]);
        $exam->setQuestions($this->createQuestions());
        $manager->persist($exam);
        $manager->flush();
    }

    /**
     * @param Classroom $classroom
     *
     * @return void
     */
    private function createProfessor(Classroom $classroom): void
    {
        $professor = new Professor();
        $professor->setFirstName($this->faker->firstName);
        $professor->setLastName($this->faker->lastName);
        $professor->setClassroom($classroom);
        $this->manager->persist($professor);
    }

    /**
     * @param Classroom $classroom
     *
     * @return void
     */
    private function createStudents(Classroom $classroom): void
    {
        for ($i = 0; $i < random_int(15, 30); $i++) {
            $student = new Student();
            $student->setFirstName($this->faker->firstName);
            $student->setLastName($this->faker->lastName);
            $student->setEmail($this->faker->email);
            $student->setClassroom($classroom);
            $this->manager->persist($student);
        }
    }

    /**
     * @return iterable
     * @throws \Exception
     */
    private function createQuestions(): iterable
    {
        $questions = [];
        for ($i = 0; $i < random_int(3, 5); $i++) {
            $a = $this->faker->numberBetween(1, 1000);
            $b = $this->faker->numberBetween(1, 1000);
            $question = new Question();
            $question->setQuestion("$a + $b");
            $question->setAnswer((string) ($a + $b));
            $this->manager->persist($question);
            $this->manager->flush();
            $this->manager->refresh($question);
            $questions[] = $question;
        }

        return $questions;
    }
}
