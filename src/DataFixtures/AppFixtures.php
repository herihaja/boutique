<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\AuthUser;
use App\Entity\AuthGroup;
use App\Entity\AuthPermission;
use App\Entity\Parametre;
use App\Entity\ParametreValeur;
use App\Entity\Personne;
use App\Entity\Produit;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $personne = new Personne();
        $personne->setPrenom("Herihaja");
        $personne->setNom("Rabenaivo");
        $personne->setTel1("0331413687");
        $personne->setAdresse("Analamahitsy Ilafy");
        $manager->persist($personne);

        $user = new AuthUser();
        $user->setEmail("hery.imiary@gmail.com");
        $user->setUsername("admin");
        $user->setIsSuperuser(true);
        $user->setPassword('$2y$13$wlgFTIsjBPDUYwTX1mL/2ewHj6YoCjDS/AHriYuoWjb5L6yxzBqt.');
        $user->setIsActive(true);
        $user->setPersonne($personne);
        $manager->persist($user);

        $parametre = new Parametre();
        $parametre->setNomParametre("Categorie produit");
        $parametre->setDescription("");
        $manager->persist($parametre);

        $categorieProduit = new ParametreValeur();
        $categorieProduit->setParametre($parametre);
        $categorieProduit->setCodeValeur("PPN");
        $categorieProduit->setValeur("Produits de premières necéssité");
        $categorieProduit->setValeur2(0);
        $categorieProduit->setDescription("");
        $manager->persist($categorieProduit);

        $categorieProduit = new ParametreValeur();
        $categorieProduit->setParametre($parametre);
        $categorieProduit->setCodeValeur("SB");
        $categorieProduit->setValeur("Santé et Beauté");
        $categorieProduit->setValeur2(0);
        $categorieProduit->setDescription("");
        $manager->persist($categorieProduit);

        $categorieProduit = new ParametreValeur();
        $categorieProduit->setParametre($parametre);
        $categorieProduit->setCodeValeur("TECH");
        $categorieProduit->setValeur("Technologie");
        $categorieProduit->setValeur2(0);
        $categorieProduit->setDescription("");
        $manager->persist($categorieProduit);

        $categorieProduit = new ParametreValeur();
        $categorieProduit->setParametre($parametre);
        $categorieProduit->setCodeValeur("ASP");
        $categorieProduit->setValeur("Articles de sport");
        $categorieProduit->setValeur2(0);
        $categorieProduit->setDescription("");
        $manager->persist($categorieProduit);

        $manager->flush();
    }
}
