<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    public function createStudent(Student $student)
    {
        $this->_em->persist($student);
        $this->_em->flush();
    }

    public function updateStudent(Student $student)
    {
        $this->_em->persist($student);
        $this->_em->flush();
    }

    public function deleteStudent(Student $student)
    {
        $this->_em->remove($student);
        $this->_em->flush();
    }
}
