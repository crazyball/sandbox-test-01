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
     * @return Classroom[]|array
     */
    public function findWithDisponibilities()
    {
        $query = $this->createQueryBuilder('c');

        return $query
            ->join('c.students', 's')
            ->groupBy('s.classroom')
            ->having($query->expr()->lte('COUNT(c)', 30))
            ->getQuery()
            ->getResult()
        ;
    }
}
