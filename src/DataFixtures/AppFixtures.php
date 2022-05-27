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
        $user->setIsSuperuser(true);
        $user->setPassword('$2y$13$2.4iPwyuGrXxCAA5BI3Z5OxlsgMHkReLMu4m5GU7clcH2apTnWGLq');
        $user->setIsActive(true);
        $user->setPersonne($personne);
        $manager->persist($user);

        $manager->flush();
    }
}
