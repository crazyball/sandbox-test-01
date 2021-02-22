<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Classroom;
use App\Entity\Professor;
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
}
