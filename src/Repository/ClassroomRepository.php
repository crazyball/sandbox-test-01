<?php

namespace App\Repository;

use App\Entity\Classroom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Classroom|null find($id, $lockMode = null, $lockVersion = null)
 * @method Classroom|null findOneBy(array $criteria, array $orderBy = null)
 * @method Classroom[]    findAll()
 * @method Classroom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClassroomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Classroom::class);
    }

    /**
     * @param Classroom $classroom
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createClassroom(Classroom $classroom)
    {
        $this->_em->persist($classroom);
        $this->_em->flush();
    }

    /**
     * @param Classroom $classroom
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateClassroom(Classroom $classroom)
    {
        $this->_em->persist($classroom);
        $this->_em->flush();
    }

    /**
     * @param Classroom $classroom
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteClassroom(Classroom $classroom)
    {
        $this->_em->remove($classroom);
        $this->_em->flush();
    }

    /**
     * Return classrooms with less than 30 classrooms in it (slots availables)
     *
     * @return Classroom[]|array
     */
    public function findWithDisponibilities()
    {
        $query = $this->createQueryBuilder('c');

        return $query
            ->join('c.classrooms', 's')
            ->groupBy('s.classroom')
            ->having($query->expr()->lte('COUNT(c)', 30))
            ->getQuery()
            ->getResult()
        ;
    }
}
