<?php

namespace App\Repository;

use App\Entity\EmailTracker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmailTracker>
 *
 * @method EmailTracker|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmailTracker|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmailTracker[]    findAll()
 * @method EmailTracker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailTrackerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailTracker::class);
    }

    public function add(EmailTracker $entity): EmailTracker
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        return $entity;    
    }

    public function remove(EmailTracker $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}
