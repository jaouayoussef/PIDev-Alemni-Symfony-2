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
    public function getNonTreatedReports()
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
    public function getTreatedReports()
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
