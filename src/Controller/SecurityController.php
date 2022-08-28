<?php

// src/Controller/SecurityController.php
namespace App\Controller;

use App\Repository\AuthUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Mime\Email;
use App\Form\ResetPasswordForm;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\AuthUser;
use App\Form\SignupForm;

class SecurityController extends AbstractController
{

    public function __construct(
        private MailerInterface $mailer,
    )
    {
        
    }

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
            'last_username' => $lastUsername,
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

    /**
     * @Route("/mot-de-passe-oublie", name="forgot_password")
     */
    public function forgotPass(
        AuthenticationUtils $authenticationUtils, 
        Request $request,
        AuthUserRepository $userRepository,
        EntityManagerInterface $em,
    ) :Response
    {
        $message = "";
        $success = false;
        if ($request->isMethod('POST')) {
            $username = $request->request->get('username');
            $user = $userRepository->findOneByIdentifiant($username);
            if ($user) {
                if ($user->getEmail()){
                    $message = $this->processResetPass($user, $mailer, $em);
                    $success = true;
                } else
                    $message = "Vous n'avez pas d'adresse e-mail enregistré. <br/> Pour ré-initialiser votre mot de passe, veuillez demander à l'administrateur du système.";
                
            }else
                $message = "Identifiant incorrecte!";
        }
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render(
            "security/forgot-pass.html.twig", 
            ['message'=> $message, 'last_username'=> $lastUsername, 'success'=> $success]
        );
    }

    private function processResetPass($user, $mailer, $entityManager){
        $token = md5($user->getUsername(). time());
        
        $user->setToken($token);
        $entityManager->persist($user);
        $entityManager->flush();

        $params = ['token' => $token, 'user'=> $user];
        $this->sendMailToUser($user, "reset-pass", "Mot de passe oublié", $params);
        return  "Email avec instruction envoyé";
    }

    private function sendMailToUser($user, $template, $subject, $parameters){
        $mailContents = $this->renderView(
            "emails/".$template.".html.twig", 
            $parameters
        );
        
        $email = (new Email())
            ->from('contact@herihaja.com')
            ->to($user->getEmail())
            ->subject($subject)
            ->html($mailContents);

        $this->mailer->send($email);

    }

    /**
     * @Route("/re-initialiser-mot-de-passe/{token}/", name="reset_password")
     */
    public function resetPass(
        Request $request, 
        AuthUserRepository $userRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordEncoder
    ):Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('dashboard');
        }
        $message = "";
        $token = $request->get('token');
        $authUser = $userRepository->findOneByToken($token);

        $form = $this->createForm(ResetPasswordForm::class, $authUser);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->getData()->getPassword();
            $authUser->setPassword(
                $passwordEncoder->hashPassword(
                    $authUser,
                    $plainPassword
                )
            );
            $authUser->setToken("");
            $entityManager->flush();

            $message = "Mot de passe ré-initialisé. Veuillez vous identifier.";
        }
 
        return $this->render(
            "security/reset-pass.html.twig", [
                'auth_users' => $authUser,
                'form' => $form->createView(),
                'error' => $message,
            ]
        );
    }

    /**
     * @Route("/inscription/", name="signup")
     */
    public function signup(
        Request $request, 
        //AuthUserRepository $userRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordEncoder
    ):Response
    {
        $authUser = new AuthUser();
        $form = $this->createForm(SignupForm::class, $authUser);
        $form->handleRequest($request);
        $message = '';
        
        if ($form->isSubmitted() && $form->isValid()) {
            $authUser->setPassword(
                $passwordEncoder->hashPassword(
                    $authUser,
                    $this->getParameter("default_password")
                )
            );
            $entityManager->persist($authUser);
            $entityManager->flush();

            $params = ['user'=> $authUser, 'password' => $this->getParameter("default_password")];
            $this->sendMailToUser($authUser, "signup", "Bienvenue sur notre application", $params);

            $message = "Vous étes enregistré, un email vous a été envoyé.";
            $authUser = new AuthUser();
            $form = $this->createForm(SignupForm::class, $authUser);
            //return $this->redirectToRoute('utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/signup.html.twig', [
            'auth_user' => $authUser,
            'form' => $form,
            'message' => $message
        ]);
    }
}
