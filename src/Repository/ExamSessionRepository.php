<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\ExamSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExamSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExamSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExamSession[]    findAll()
 * @method ExamSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExamSession::class);
    }

    public function save(ExamSession $examSession): void
    {
        $this->getEntityManager()->persist($examSession);
        $this->getEntityManager()->flush();
    }
}
