<?php

namespace App\Repository;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;  
use  League\OAuth2\Client\Provider\GithubResourceOwner;


/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function findOrCreateFromOauth(GithubResourceOwner $owner): User
    {
        $user = $this->createQueryBuilder('u')
        ->where('u.githubID = :githubID')
        ->setParameters([
            'githubID' => $owner->getId(),
        ])->getQuery()->getOneOrNullResult();

        if($user){
            if($user->getGithubID()===null)
            {
                $user->setGithubID($owner->getId());
                $this->getEntityManager()->flush();
            }
            return $user;
        }

        $user = (new User())
        ->setRoles(['ROLE_USER'])
        ->setGithubId($owner->getId())
        ->setIsBanned(0)
        ->setIsVerified(1)
        ->setCreationDate(new \DateTime())
        ->setEmail($owner->getEmail());
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        return $user;
    }
}
