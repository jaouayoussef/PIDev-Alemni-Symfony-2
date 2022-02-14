<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    public function getAllAnswers()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT reclamation.id, reclamation.titre,reclamation.message,reclamation.sending_date,reclamation.type,reclamation.status,reclamation.email, reclamation.fullname, reclamation.user_file, reponse.reclamation_id_id,reponse.answer, reponse.file, reponse.reply_date FROM reclamation 
        LEFT JOIN reponse ON reponse.reclamation_id_id = reclamation.id 
        UNION 
        SELECT reclamation.id, reclamation.titre,reclamation.message,reclamation.sending_date,reclamation.type,reclamation.status,reclamation.email, reclamation.fullname, reclamation.user_file, reponse.reclamation_id_id,reponse.answer, reponse.file, reponse.reply_date FROM reclamation 
        RIGHT JOIN reponse ON reponse.reclamation_id_id = reclamation.id';

        $stmt = $conn->prepare($sql);
        $result =  $stmt->executeQuery();

        return $result->fetchAllAssociative();
    }

    // /**
    //  * @return Reclamation[] Returns an array of Reclamation objects
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
    public function findOneBySomeField($value): ?Reclamation
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
