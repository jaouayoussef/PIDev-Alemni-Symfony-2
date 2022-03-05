<?php

namespace App\Repository;

use App\Entity\Promotion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Promotion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promotion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promotion[]    findAll()
 * @method Promotion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromotionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promotion::class);
    }
    public function getPromotionbydatenow(){
        $qb = $this->createQueryBuilder('u');
        $qb->where('u.P_DateF >= :identifier')
            ->setParameter('identifier', new \DateTime('now'));

        return $qb->getQuery()->getResult();
    }
    public function CountBydatePromo(){
        $query = $this->createQueryBuilder('a')
            ->select('SUBSTRING(a.P_DateF,1,10) as datePromo, COUNT(a) as count')
            -> groupBy('datePromo');
        return $query->getQuery()->getResult();
    }
    public function getPromotionEVENTbydatenow(){
        $qb = $this->createQueryBuilder('u');
        $qb->where('u.P_DateF >= :identifier and u.P_DateB <= :identifier and u.event is not null')
            ->setParameter('identifier', new \DateTime('now'));

        return $qb->getQuery()->getResult();
    }
    // /**
    //  * @return Promotion[] Returns an array of Promotion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Promotion
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
