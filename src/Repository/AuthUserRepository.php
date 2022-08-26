<?php

namespace App\Repository;

use App\Entity\AuthUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AuthUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuthUser[]    findAll()
 * @method AuthUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuthUser::class);
    }

    // /**
    //  * @return AuthUser[] Returns an array of AuthUser objects
    //  */

    public function findByGroupName($groupList)
    {
        $query = $this->createQueryBuilder('a')
            ->join("a.groups", "g")
            ->andWhere('g.name in (:val)')
            ->setParameter('val', $groupList)
            ->orderBy('a.id', 'ASC')
            ->getQuery()

        ;
        return $query->getResult();
    }

    
    public function findOneByIdentifiant($value): ?AuthUser
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.username = :val')
            ->orWhere('a.email = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }


    ///**
    //  * @return AuthUser[] Returns an array of AuthUser objects
    //  * $groupList is a list of group eg: ['DFC', 'DPSE']
    //  */

    public function findByDirectionInddl($groupList)
    {
        $query = $this->createQueryBuilder('u')
            ->join("u.personne", "p")
            ->join("p.agent", "a")
            ->join("a.organigrammes", "r") //rattachement
            ->join("r.organigramme", "o")
            ->orderBy('u.id', 'ASC');

        if (count($groupList)) {
            $query = $query->andWhere('o.sigle in (:val)')
                ->setParameter('val', $groupList);
        }
        return $query->getQuery()->getResult();
    }
}
