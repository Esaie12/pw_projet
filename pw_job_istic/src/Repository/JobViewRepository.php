<?php

namespace App\Repository;

use App\Entity\JobView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobView>
 */
class JobViewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobView::class);
    }

    public function countViewsByJob(JobPosting $job): int
    {
        return $this->createQueryBuilder('jv')
            ->select('COUNT(jv.id)')
            ->where('jv.job = :job')
            ->setParameter('job', $job)
            ->getQuery()
            ->getSingleScalarResult();
    }

    //    /**
    //     * @return JobView[] Returns an array of JobView objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('j.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?JobView
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
