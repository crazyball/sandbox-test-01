<?php

namespace App\Repository;

use App\Entity\Classroom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
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
    public function findWithDisponibilities(): iterable
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

    public function haveCurrentExamOpened(int $classroomId): bool
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('nbExams', 'nbExams', 'integer');

        return $this->getEntityManager()
            ->createNativeQuery(
<<<SQL
    SELECT COUNT(*) as nbExams
    FROM classroom c 
    LEFT JOIN exam e ON e.classroom_id = c.id
    WHERE (SELECT COUNT(*) FROM exam_session es WHERE es.exam_id = e.id AND es.answer IS NULL) <> 
          (SELECT COUNT(*) FROM exam_question eq WHERE eq.exam_id = e.id)
    AND c.id = $classroomId;
SQL
                ,
                $rsm
            )
            ->getSingleScalarResult();
    }
}
