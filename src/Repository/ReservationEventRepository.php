<?php

namespace App\Repository;

use App\Entity\ReservationEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReservationEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationEvent[]    findAll()
 * @method ReservationEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationEvent::class);
    }
    /**
     * @return void
     */
    public function AvgBydate(){
        $query = $this->createQueryBuilder('a')
            ->select('SUBSTRING(a.DateReservationEvent,6,2) as dateMonth, SUM(a.PrixReservationEvent) as SUM')
            ->where('SUBSTRING(a.DateReservationEvent,1,4) = 2022 ')
            -> groupBy('dateMonth');
        return $query->getQuery()->getResult();
    }
    public function getbyuser($userId){
        $qb = $this->createQueryBuilder('u');
        $qb->where('u.UserId = :identifier')
            ->setParameter('identifier', $userId);
        return $qb->getQuery()->getResult();
    }
    // /**
    //  * @return ReservationEvent[] Returns an array of ReservationEvent objects
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

    /*
    public function findOneBySomeField($value): ?ReservationEvent
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
