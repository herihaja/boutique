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
                join stock s ON s.produit_id = p.id and s.quantite > 0
                Group by p.id
                ORDER BY p.nom ASC"

        );

        return $query->fetchAllAssociative(); //\Doctrine\ORM\Query::HYDRATE_SCALAR);
    }

    public function getAllWithoutPrice()
    {
        $con = $this->getEntityManager()->getConnection();
        
        $query = $con->exeCuteQuery(
            "SELECT p.nom, p.id, GROUP_CONCAT(0, ';', u.valeur, ';', u.id, ';', 0 SEPARATOR '|') as prices FROM `produit` p 
                join produit_unites px ON px.produit_id = p.id 
                join parametre_valeur u ON u.id = px.parametre_valeur_id
                Group by p.id
                ORDER BY p.nom ASC"

        );

        return $query->fetchAllAssociative();

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
                join produit_unites px ON px.produit_id = p.id
                join stock stk ON stk.produit_id = p.id and px.parametre_valeur_id = stk.unite_id 
                join parametre_valeur u ON u.id = px.parametre_valeur_id
                Group by p.id
                ORDER BY p.nom ASC"

        );

        return $query->fetchAllAssociative();
    }

    public function getDailySaleProduct(){
        $con = $this->getEntityManager()->getConnection();
        $query = $con->exeCuteQuery(
            "select t.nom, GROUP_CONCAT(t.jour) as dates, GROUP_CONCAT(t.frequence) as frequency FROM 
                (SELECT p.nom, p.id, count(mi.id) as frequence, Date(m.date_mouvement) as jour FROM `produit` p 
                    join mouvement_item mi ON mi.produit_id = p.id
                    join mouvement m ON m.id = mi.mouvement_id
                    where m.is_vente = true
                    Group by p.id, DATE(m.date_mouvement)
                    ORDER BY date(m.date_mouvement) ASC
                ) as t GROUP BY t.id"
        );

        $dateQuery = $con->exeCuteQuery(
            "select Distinct(DATE(m.date_mouvement)) as dates from mouvement as m where m.is_vente = true"
        );

        $dateResult = $dateQuery->fetchAllAssociative();

        $results = $query->fetchAllAssociative();
        $labels = [];
        $datas = [];
        foreach ($results as $produit) {
            $dates = explode(",", $produit['dates']);
            $frequences = explode(",", $produit['frequency']);
            $data = [];
        
            foreach($dateResult as $dateItem) {
                $date = $dateItem['dates'];
                if (!in_array($date, $labels))
                    $labels[] = $date;

                if (in_array($date, $dates)){
                    $index = array_search($date, $dates);
                    $data[] = $frequences[$index];
                } else {
                    $data[] = 0;
                }                
            }
            $datas[] = ['name'=>$produit['nom'], 'data'=>$data];
        }
        return [$datas, implode('", "', $labels)];
    }

    public function getSaleFrequencyLast30Days(){
        $con = $this->getEntityManager()->getConnection();
        $query = $con->exeCuteQuery(
            "select t.nom, GROUP_CONCAT(t.frequence) as frequency FROM 
                (SELECT p.nom, p.id, count(mi.id) as frequence FROM `produit` p 
                    join mouvement_item mi ON mi.produit_id = p.id
                    join mouvement m ON m.id = mi.mouvement_id
                    where m.is_vente = true and m.date_mouvement >= DATE_ADD(CURDATE(), INTERVAL -30 DAY)
                    Group by p.id
                ) as t GROUP BY t.id"
        );

        $results = $query->fetchAllAssociative();
        $labels = [];
        $datas = [];
        foreach ($results as $produit) {
            $frequences = explode(",", $produit['frequency']);
            $datas[] = ['name'=>$produit['nom'], 'data'=> [$frequences[0]]];
        }

        return [$datas, implode('", "', $labels)];
    }

    public function compareStockAndSales(){
        $con = $this->getEntityManager()->getConnection();
        $query = $con->executeQuery(
            "select t.nom, SUM(t.quantite) as quantite, SUM(t.stk) as stock FROM 
                (SELECT CONCAT(p.nom, ' (' ,u.valeur, ')') as nom, p.id, u.valeur, SUM(mi.nombre) as quantite, s.quantite as stk FROM `produit` p 
                    join mouvement_item mi ON mi.produit_id = p.id
                    join mouvement m ON m.id = mi.mouvement_id
                    join prix px ON px.id = mi.prix_ut_id
                    join parametre_valeur u ON u.id = px.unite_id
                    join stock s ON s.unite_id = u.id and p.id = s.produit_id
                    where m.is_vente = true and m.date_mouvement >= DATE_ADD(CURDATE(), INTERVAL -7 DAY)
                    Group by p.id, u.valeur, s.id
                ) as t GROUP BY t.id, t.nom"
        );

        $results = $query->fetchAllAssociative();
        
        $labels = [];
        $stock = [];
        $vente = [];
        foreach ($results as $produit) {
            $labels[] = $produit['nom'];
            $stock[] = $produit['stock']; 
            $vente[] = $produit['quantite'];                
        }

        $datas[] = ['name' => "Vente", 'data' => $vente];
        $datas[] = ['name' => "Stock", 'data' => $stock];
        return [$datas, implode('", "', $labels)];
    }



    public function getDailySale(){
        $con = $this->getEntityManager()->getConnection();
        $query = $con->exeCuteQuery(
            "select t.*  from (SELECT SUM(m.montant_total) as total, Date(m.date_mouvement) as jour FROM mouvement m
                    where m.is_vente = true and m.date_mouvement >= DATE_ADD(CURDATE(), INTERVAL -30 DAY)
                    Group by DATE(m.date_mouvement)
                    ORDER BY date(m.date_mouvement) DESC
                    LIMIT 5) as t order by t.jour ASC
                "
        );

        $results = $query->fetchAllAssociative();
        $labels = [];
        $datas = [];
        foreach ($results as $sale) {
            $labels[] = $sale['jour'];
            $datas[] = ($sale['total']);
        }
        return [[$datas], implode('", "', $labels)];
    }
}
