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
        $parametre->setNomParametre("Categorie produit"); //1
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

        $parametre = new Parametre();
        $parametre->setNomParametre("Type produit"); //2
        $parametre->setDescription("");
        $manager->persist($parametre);

        $categorieProduit = new ParametreValeur();
        $categorieProduit->setParametre($parametre);
        $categorieProduit->setCodeValeur("STK");
        $categorieProduit->setValeur("Stockable");
        $categorieProduit->setValeur2(0);
        $categorieProduit->setDescription("On peut stocker");
        $manager->persist($categorieProduit);

        $categorieProduit = new ParametreValeur();
        $categorieProduit->setParametre($parametre);
        $categorieProduit->setCodeValeur("NST");
        $categorieProduit->setValeur("Non stockable");
        $categorieProduit->setValeur2(0);
        $categorieProduit->setDescription("On ne peut pas stocker");
        $manager->persist($categorieProduit);

        $parametre = new Parametre();
        $parametre->setNomParametre("Unité produit"); //3
        $parametre->setDescription("");
        $manager->persist($parametre);

        $categorieProduit = new ParametreValeur();
        $categorieProduit->setParametre($parametre);
        $categorieProduit->setCodeValeur("sac");
        $categorieProduit->setValeur("Sac");
        $categorieProduit->setValeur2(0);
        $categorieProduit->setDescription("");
        $manager->persist($categorieProduit);

        $categorieProduit = new ParametreValeur();
        $categorieProduit->setParametre($parametre);
        $categorieProduit->setCodeValeur("bouteille");
        $categorieProduit->setValeur("Bouteille");
        $categorieProduit->setValeur2(0);
        $categorieProduit->setDescription("");
        $manager->persist($categorieProduit);

        $categorieProduit = new ParametreValeur();
        $categorieProduit->setParametre($parametre);
        $categorieProduit->setCodeValeur("carton");
        $categorieProduit->setValeur("Carton");
        $categorieProduit->setValeur2(0);
        $categorieProduit->setDescription("");
        $manager->persist($categorieProduit);

        $categorieProduit = new ParametreValeur();
        $categorieProduit->setParametre($parametre);
        $categorieProduit->setCodeValeur("paquet");
        $categorieProduit->setValeur("Paquet");
        $categorieProduit->setValeur2(0);
        $categorieProduit->setDescription("");
        $manager->persist($categorieProduit);

        $categorieProduit = new ParametreValeur();
        $categorieProduit->setParametre($parametre);
        $categorieProduit->setCodeValeur("cartouche");
        $categorieProduit->setValeur("Cartouche");
        $categorieProduit->setValeur2(0);
        $categorieProduit->setDescription("");
        $manager->persist($categorieProduit);

        $categorieProduit = new ParametreValeur();
        $categorieProduit->setParametre($parametre);
        $categorieProduit->setCodeValeur("kapoaka");
        $categorieProduit->setValeur("Kapoaka");
        $categorieProduit->setValeur2(0);
        $categorieProduit->setDescription("");
        $manager->persist($categorieProduit);

        $categorieProduit = new ParametreValeur();
        $categorieProduit->setParametre($parametre);
        $categorieProduit->setCodeValeur("sachet");
        $categorieProduit->setValeur("Sachet");
        $categorieProduit->setValeur2(0);
        $categorieProduit->setDescription("");
        $manager->persist($categorieProduit);

        $categorieProduit = new ParametreValeur();
        $categorieProduit->setParametre($parametre);
        $categorieProduit->setCodeValeur("kg");
        $categorieProduit->setValeur("Kilogramme");
        $categorieProduit->setValeur2(0);
        $categorieProduit->setDescription("");
        $manager->persist($categorieProduit);

        $categorieProduit = new ParametreValeur();
        $categorieProduit->setParametre($parametre);
        $categorieProduit->setCodeValeur("litre");
        $categorieProduit->setValeur("Litre");
        $categorieProduit->setValeur2(0);
        $categorieProduit->setDescription("");
        $manager->persist($categorieProduit);

        $categorieProduit = new ParametreValeur();
        $categorieProduit->setParametre($parametre);
        $categorieProduit->setCodeValeur("m3");
        $categorieProduit->setValeur("m3");
        $categorieProduit->setValeur2(0);
        $categorieProduit->setDescription("");
        $manager->persist($categorieProduit);

        $categorieProduit = new ParametreValeur();
        $categorieProduit->setParametre($parametre);
        $categorieProduit->setCodeValeur("unite");
        $categorieProduit->setValeur("Unité");
        $categorieProduit->setValeur2(0);
        $categorieProduit->setDescription("");
        $manager->persist($categorieProduit);

        $manager->flush();
    }
}
