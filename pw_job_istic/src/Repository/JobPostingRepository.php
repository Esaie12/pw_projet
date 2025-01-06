<?php

namespace App\Repository;

use App\Entity\JobPosting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
/**
 * @extends ServiceEntityRepository<JobPosting>
 */
class JobPostingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobPosting::class);
    }

    public function findMostPopularJobs(int $limit = 3): array
    {
        return $this->createQueryBuilder('j')
            ->select('j, COUNT(v.id) AS HIDDEN viewCount')
            ->leftJoin('j.views', 'v')
            ->groupBy('j.id')
            ->orderBy('viewCount', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByFilters(array $criteria): QueryBuilder
    {
       
        
        if(isset($criteria['job_filter_welcome'])){
            $criteria = $criteria['job_filter_welcome'];
        }elseif(isset($criteria['job_filter_job_page'])){
            $criteria = $criteria['job_filter_job_page'];
        }else{
            $criteria=[];
        }
       
        $qb = $this->createQueryBuilder('job_posting');

        // Filtrer par titre
        if (!empty($criteria['title'])) {
            $qb->andWhere('job_posting.title LIKE :title')
               ->setParameter('title', '%' . $criteria['title'] . '%');
        }

        // Filtrer par localisation
        if (!empty($criteria['location'])) {
            $qb->andWhere('job_posting.location LIKE :location')
               ->setParameter('location', '%' . $criteria['location'] . '%');
        }

        // Filtrer par type de job
        /*if (!empty($criteria['jobType'])) {
            $qb->andWhere('job.jobType = :jobType')
               ->setParameter('jobType', $criteria['jobType']);
        }*/

        // Filtrer par langages
        if (!empty($criteria['technologies'])) {
            $qb->join('job_posting.technologies', 'tech')
               ->andWhere('tech IN (:technologies)')
               ->setParameter('technologies', $criteria['technologies']);
        }

        if (!empty($criteria['experienceLevel'])) {
            $qb->andWhere('job_posting.experienceLevel = :experienceLevel')
               ->setParameter('experienceLevel', $criteria['experienceLevel']);
        }
       // $qb->orderBy('job_posting.publishedAt', 'DESC');

        return $qb;
        /** Si on avait pas faire une pagination */
        //return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return JobPosting[] Returns an array of JobPosting objects
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

    //    public function findOneBySomeField($value): ?JobPosting
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
