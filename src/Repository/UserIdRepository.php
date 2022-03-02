<?php

namespace App\Repository;

use App\Entity\UserId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserId|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserId|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserId[]    findAll()
 * @method UserId[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserIdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserId::class);
    }

    // /**
    //  * @return UserId[] Returns an array of UserId objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserId
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
