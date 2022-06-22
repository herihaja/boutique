<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function add(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAllWithPrice()
    {
        $con = $this->getEntityManager()->getConnection();
        $rsm = new ResultSetMapping();
        $query = $con->exeCuteQuery(
            "SELECT p.nom, p.id, GROUP_CONCAT(px.valeur, ';', u.valeur, ';', u.id, ';', px.id SEPARATOR '|') as prices FROM `produit` p 
                join prix px ON px.produit_id = p.id 
                join parametre_valeur u ON u.id = px.unite_id
                Group by p.id"

        );

        return $query->fetchAllAssociative(); //\Doctrine\ORM\Query::HYDRATE_SCALAR);

    }

    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function findByExampleField($value): array
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

    public function getAllStock()
    {
        $con = $this->getEntityManager()->getConnection();
        $query = $con->exeCuteQuery(
            "SELECT p.nom, p.id, GROUP_CONCAT(stk.quantite, ' ', u.valeur, '(s)' SEPARATOR ', ') as stocks FROM `produit` p 
                join prix px ON px.produit_id = p.id
                join stock stk ON stk.produit_id = p.id and px.id = stk.prix_id 
                join parametre_valeur u ON u.id = px.unite_id
                Group by p.id"

        );

        return $query->fetchAllAssociative();

    }

    //    public function findOneBySomeField($value): ?Produit
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
