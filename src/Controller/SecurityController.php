<?php

// src/Controller/SecurityController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // Retrive the last email entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
   

        return $this->render('security/login.html.twig', [
            'last_username' => '',
            'error' => $error
        ]);
    }



    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): Response
    {
        // controller can be blank: it will never be executed!
    }
}
