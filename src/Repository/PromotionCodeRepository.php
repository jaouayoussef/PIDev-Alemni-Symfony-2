<?php

namespace App\Repository;

use App\Entity\PromotionCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PromotionCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method PromotionCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method PromotionCode[]    findAll()
 * @method PromotionCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromotionCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PromotionCode::class);
    }
    public function getWhatYouWantPromotionCode($id)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->where('u.id != :identifier')
            ->setParameter('identifier', $id);

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @return void
     */
    public function CountBydate(){
        $query = $this->createQueryBuilder('a')
            ->select('SUBSTRING(a.PC_ExpirationCode,1,10) as datePromo, COUNT(a) as count')
            -> groupBy('datePromo');
        return $query->getQuery()->getResult();
    }
    public function getPromotionCodebydatenow(){
        $qb = $this->createQueryBuilder('u');
        $qb->where('u.PC_ExpirationCode >= :identifier')
            ->setParameter('identifier', new \DateTime('now'));

        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return PromotionCode[] Returns an array of PromotionCode objects
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
    public function findOneBySomeField($value): ?PromotionCode
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
