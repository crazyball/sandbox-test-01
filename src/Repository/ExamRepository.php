<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Exam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Exam|null find($id, $lockMode = null, $lockVersion = null)
 * @method Exam|null findOneBy(array $criteria, array $orderBy = null)
 * @method Exam[]    findAll()
 * @method Exam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exam::class);
    }

    public function save(Exam $exam)
    {
        $this->getEntityManager()->persist($exam);
        $this->getEntityManager()->flush();
    }

    /**
     * @param int $classroomId
     *
     * @return Exam[]|iterable
     */
    public function findExamsForClassroom(int $classroomId): iterable
    {
        return $this
            ->createQueryBuilder('e')
            ->leftJoin('e.classroom', 'c')
            ->where('c.id = :classroomId')
            ->setParameter('classroomId', $classroomId)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find active or available exam for given student
     *
     * @return Exam|null
     */
    public function findExamForStudent(int $studentId): ?Exam
    {
        $exams = $this->createQueryBuilder('e')
            ->leftJoin('e.classroom', 'c')
            ->leftJoin('e.questions', 'q')
            ->leftJoin('c.students', 's')
            ->leftJoin('e.sessions', 'es')
            ->leftJoin('es.answers', 'esa')
            ->where('s.id = :studentId')
            ->having("COUNT(esa.id) <> COUNT(e.id)")
            ->setParameter('studentId', $studentId)
            ->getQuery()
            ->getResult();

        return is_array($exams) && count($exams) > 0 ? $exams[0] : null;
    }
}

