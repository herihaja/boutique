<?php

namespace App\AppBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\UserNotification;


class AppExtension extends AbstractExtension
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->conn = $em->getConnection();
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_information', [$this, 'getInfo']),
        ];
    }

    public function calculateArea(int $width, int $length)
    {
        return $width * $length;
    }

    public function getInfo($user)
    {
        return []; //$this->em->getRepository(UserNotification::class)
        //->findUnreadByUser($user->getId());
    }
}
