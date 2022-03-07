<?php

namespace App\Repository;

use App\Entity\ReservationFormation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReservationFormation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationFormation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationFormation[]    findAll()
 * @method ReservationFormation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationFormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationFormation::class);
    }

    // /**
    //  * @return ReservationFormation[] Returns an array of ReservationFormation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findOneBySomeField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }


}
