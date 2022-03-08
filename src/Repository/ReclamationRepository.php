<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
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

    public function countbydate()
    {
        $query = $this->createQueryBuilder('a')
            ->select('SUBSTRING(a.sending_date,1,7)as date_reclamtion , count(a) as count')
            ->groupBy('date_reclamtion');
        return $query->getQuery()->getResult();

    }

    /**
     * @throws Exception
     */
    public function getAllAnswers(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT reclamation.id, reclamation.title,reclamation.message,reclamation.sending_date,reclamation.type,reclamation.status,reclamation.email, reclamation.name, reclamation.user_file,reponse.id AS reponseId, reponse.reclamation_id,reponse.answer, reponse.admin_file, reponse.reply_date FROM reclamation 
        LEFT JOIN reponse ON reponse.reclamation_id = reclamation.id 
        UNION 
        SELECT reclamation.id, reclamation.title,reclamation.message,reclamation.sending_date,reclamation.type,reclamation.status,reclamation.email, reclamation.name, reclamation.user_file, reponse.id AS reponseId, reponse.reclamation_id,reponse.answer, reponse.admin_file, reponse.reply_date FROM reclamation 
        RIGHT JOIN reponse ON reponse.reclamation_id = reclamation.id';

        $stmt = $conn->prepare($sql);
        $result =  $stmt->executeQuery();

        return $result->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function getNonTreatedReports(): int
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT * FROM reclamation WHERE status = 0';

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();
        return $result->rowCount();
    }

    /**
     * @throws Exception
     */
    public function getTreatedReports(): int
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT * FROM reclamation WHERE status = 1';

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();
        return $result->rowCount();
    }

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
