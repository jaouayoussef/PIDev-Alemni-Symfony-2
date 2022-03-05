<?php

namespace App\Repository;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Formation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formation[]    findAll()
 * @method Formation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }
    public function getFormation(){

       return $this->getEntityManager()
           ->createQuery('select f from App\Entity\Formation f WHERE f  NOT IN (select  s from App\Entity\Seance s WHERE s.dateSeance >= :date)')
           ->setParameter('date' , new \DateTime('now'))
           ->getResult();

       /* return $this->getEntityManager()
            ->createQuery('SELECT
        f.id,
        f.nomFormation,
        f.descriptionFormation,
        f.lien,
        f.prixFormation,
        f.imageFormation,
        f.formateur,
        f.nbPlaces,

    FROM
                App\Entity\Formation f
        LEFT JOIN
                f.seances s


    WHERE
           (s.dateSeance < :date)
       ')
            ->setParameter('date' , new \DateTime('now'))
            ->getResult();*/
    }
    public function getFormationDispo()
    {
        return $this->createQueryBuilder('q')
            ->expr()->notIn('q.formation',array('?1'))
            ->setParameter(1, 100)
            ->orderBy('q.dateSeance', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    // /**
    //  * @return Formation[] Returns an array of Formation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /**
    * @return Formation[] Returns an array of Formation objects
    */
    public function getFormationByUser($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.formateur = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

}
