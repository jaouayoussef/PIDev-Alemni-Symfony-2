<?php

namespace App\Repository;

use App\Entity\Useranswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Useranswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Useranswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Useranswer[]    findAll()
 * @method Useranswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UseranswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Useranswer::class);
    }

    // /**
    //  * @return Useranswer[] Returns an array of Useranswer objects
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
    public function findOneBySomeField($value): ?Useranswer
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
