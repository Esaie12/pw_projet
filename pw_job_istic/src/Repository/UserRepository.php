<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**les dev populaires */
    
    public function popularDevs(): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.type_user = :type')
            ->setParameter('type', 'dev')
            ->andWhere('u.isActive = :active')
            ->setParameter('active', true)
            ->getQuery()
            ->getResult();
    }


    /**
     * Récupère les 3 derniers utilisateurs créés
     */
    public function findLastCreatedDevs(int $limit = 3): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.type_user = :type')
            ->setParameter('type', 'dev')
            ->andWhere('u.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('u.id', 'DESC') 
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findActiveUsersExcludingCurrent(int $currentUserId): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.isActive = :active') // Filtre utilisateurs actifs
            ->andWhere('u.id != :currentId') // Exclure l'utilisateur connecté
            ->setParameter('active', true)
            ->setParameter('currentId', $currentUserId)
            ->orderBy('u.id', 'ASC') // Facultatif : trier par nom
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
