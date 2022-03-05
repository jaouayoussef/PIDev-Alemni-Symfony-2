<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }
    public function geteventbydatenowandreservation($listid){
        $qb = $this->createQueryBuilder('u');
        $qb->where('u.E_DateDebut >= :identifier AND u.id NOT IN (:listid) AND u.E_PlaceReserver < u.E_Nbre')
            ->setParameter('identifier', new \DateTime('now'))
            ->setParameter('listid', $listid);
        return $qb->getQuery()->getResult();
    }
    public function geteventbydatenow(){
        $qb = $this->createQueryBuilder('u');
        $qb->where('u.E_DateDebut >= :identifier AND u.E_PlaceReserver < u.E_Nbre')
            ->setParameter('identifier', new \DateTime('now'));
        return $qb->getQuery()->getResult();
    }
    public function CountBydatedebut(){
        $query = $this->createQueryBuilder('a')
            ->select('SUBSTRING(a.E_DateDebut,1,10) as datedebut, COUNT(a) as count')
            ->where('a.E_DateDebut >= :identifier')
            ->setParameter('identifier', new \DateTime('now'))
            -> groupBy('datedebut');
        return $query->getQuery()->getResult();
    }
    public function CountBydatefin(){
        $query = $this->createQueryBuilder('a')
            ->select('SUBSTRING(a.E_DateFin,1,10) as datefin, COUNT(a) as countfin')
            ->where('a.E_DateDebut >= :identifier')
            ->setParameter('identifier', new \DateTime('now'))
            -> groupBy('datefin');
        return $query->getQuery()->getResult();
    }
    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
