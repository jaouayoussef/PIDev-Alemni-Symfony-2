<?php

namespace App\Repository;

use App\Entity\Userresult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Userresult|null find($id, $lockMode = null, $lockVersion = null)
 * @method Userresult|null findOneBy(array $criteria, array $orderBy = null)
 * @method Userresult[]    findAll()
 * @method Userresult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserresultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Userresult::class);
    }

    // /**
    //  * @return Userresult[] Returns an array of Userresult objects
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
    public function findOneBySomeField($value): ?Userresult
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
