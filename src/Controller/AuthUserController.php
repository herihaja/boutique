<?php

namespace App\Controller;

use App\Entity\AuthUser;
use App\Entity\AuthGroup;
use App\Entity\Agent;
use App\Entity\Personne;
use App\Entity\AllConstants;
use App\Entity\RenforcementCapacites;
use App\Entity\RessourceHumaine;
use App\Form\AuthUserType;
use App\Form\AuthUserPermissionType;
use App\Form\ResetPasswordForm;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

//use Symfony\Component\Mailer\Bridge\Google\Smtp\GmailTransport;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Component\Mailer\Mailer;



/**
 * @Route("/utilisateur")
 */
class AuthUserController extends AbstractController
{
    use TraitCommonMethods;

    /**
     * @Route("/construction/", name="page_en_construction", methods={"GET"})
     */
    public function enConstruction(Request $request): Response
    {
        return $this->render('auth_user/construction.html.twig');
    }

    /**
     * @Route("/dashboard/", name="dashboard", methods={"GET"})
     */
    public function dashboard(Request $request, ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();



        return $this->render(
            'auth_user/dashboard.html.twig',
            [
                'items' => [
                    //'Repartition RH par CTD' => $repartitionParCtd,
                ],
                'agentsEnMission' => 0,
                'agentsNonDisponible' => 0,
                'agentsDisponible' => 0,
                'rcEnPreparation' => 0,
                'rcRealisees' => 0,
                'rcEnCours' => 0
            ]
        );
    }

    /**
     * @Route("/liste{id}/", name="auth_user_liste", methods={"GET"})
     */
    public function liste(Request $request, AuthGroup $groupe): Response
    {
        $authUsers = $groupe->getUsers();

        return $this->render('auth_user/index.html.twig', [
            'auth_users' => $authUsers,
            'tabs' => $this->getTabsUser($groupe, "utilisateur", array()),
        ]);
    }


    /**
     * @Route("/", name="auth_user_index", methods={"GET"})
     */
    public function index(): Response
    {
        $authUsers = $this->getDoctrine()
            ->getRepository(AuthUser::class)
            ->findAll();

        return $this->render('auth_user/index.html.twig', [
            'auth_users' => $authUsers,
        ]);
    }

    /**
     * @Route("/new{personne}", name="auth_user_new", methods={"GET","POST"}, defaults={"personne"=0})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $authUser = new AuthUser();
        $agentId = $request->get("agent");
        if ($agentId) {
            $agent = $this->getDoctrine()->getRepository(Agent::class)->find($agentId);
            if ($agent->getUtilisateur())
                return $this->redirectToRoute('agent_index');
            $authUser->setAgent($agent);
        }

        $personneId = $request->get("personne");
        if ($personneId) {
            $personne = $this->getDoctrine()->getRepository(Personne::class)->find($personneId);

            if ($personne->getUtilisateur()) {
                return $this->redirectToRoute("auth_user_edit", array('id' => $personne->getUtilisateur()->getId()));
            }
            $authUser->setPersonne($personne);
        }

        $form = $this->createForm(AuthUserType::class, $authUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $authUser->setPassword(
                $passwordEncoder->encodePassword(
                    $authUser,
                    AllConstants::DEFAULT_PASSWORD
                )
            );

            /*  handle the case the new user is an agent as well  */
            if ($request->request->get('is_agent') == "1") {
                $agent = $authUser->getPersonne()->createAgent();
                $entityManager->persist($agent);
            }

            $entityManager->persist($authUser);
            $entityManager->flush();

            return $this->redirectToRoute('auth_user_index');
        }

        return $this->render('auth_user/new.html.twig', [
            'auth_user' => $authUser,
            'form' => $form->createView(),
            //'tabs' => $this->getTabsUser("utilisateur", array()),
        ]);
    }



    /**
     * @Route("/{id}/edit", name="auth_user_edit", methods={"GET","POST"}, defaults={"activeTab"="utilisateur"})
     */
    public function edit(Request $request, AuthUser $authUser): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(AuthUserType::class, $authUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($request->request->get('is_agent', 0) == "1") {
                $agent = $authUser->getPersonne()->createAgent();
                $entityManager->persist($agent);
            }
            $entityManager->flush();

            return $this->redirectToRoute('auth_user_index');
        }

        return $this->render('auth_user/edit.html.twig', [
            'auth_user' => $authUser,
            'form' => $form->createView(),
            'tabs' => $this->getTabsUser($authUser, "info. générale", array()),
        ]);
    }

    /**
     * @Route("/sendmail/", name="test_mail", methods={"GET"})
     */
    public function sendMail(Request $request, \Swift_Mailer $mailer)
    {
        $name = "After cache clear...";
        $message = (new \Swift_Message('SIMINDDL: Hello Email'))
            ->setFrom('mamydafy@gmail.com')
            ->setTo('hery.imiary@gmail.com')
            ->setBody(
                $this->renderView(
                    // templates/emails/registration.html.twig
                    'emails/registration.html.twig',
                    ['name' => $name]
                ),
                'text/html'
            )
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'emails/registration.txt.twig',
                    ['name' => $name]
                ),
                'text/plain'
            )
            */;

        $mailer->send($message);


        return $this->render('auth_user/construction.html.twig');
    }





    /**
     * @Route("/profil_utilisateur", name="user_profil", methods={"GET", "POST"})
     */
    public function userProfil(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response {

        $response =  $this->forward('App\Controller\AuthUserController::edit', [
            'request'  => $request,
            'authUser' => $this->getUser(),
            'activeTab' => "profil",
        ]);

        return $response;
    }

    /**
     * @Route("/resetpassword", name="user_pwdreset", methods={"GET", "POST"})
     */
    public function resetPassword(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response {
        $authUser = $this->getUser();
        $form = $this->createForm(ResetPasswordForm::class, $authUser);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->getData()->getPassword();
            $authUser->setPassword(
                $passwordEncoder->encodePassword(
                    $authUser,
                    $plainPassword
                )
            );
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('auth_user_edit', ['id' => $authUser->getId()]);
        }

        return $this->render('auth_user/resetpassword.html.twig', [
            'auth_users' => $authUser,
            'form' => $form->createView(),
            'tabs' => $this->getTabsUser($authUser, "utilisateur", array()),
        ]);
    }

    /**
     * @Route("/{id}", name="auth_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, AuthUser $authUser): Response
    {
        if ($this->isCsrfTokenValid('delete' . $authUser->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($authUser);
            $entityManager->flush();
        }
        return $this->redirectToRoute('auth_user_index');
    }

    /**
     * @Route("/{id}", name="auth_user_show", methods={"GET"})
     */
    public function show(AuthUser $authUser): Response
    {
        return $this->render('auth_user/show.html.twig', [
            'auth_user' => $authUser,
        ]);
    }


    /**
     * @Route("/{id}/permission", name="auth_user_permission", methods={"GET","POST"}, defaults={"activeTab"="utilisateur"})
     */
    public function permission(Request $request, AuthUser $authUser): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        foreach ($authUser->getGroups() as $g) {
            $g->removeUser($authUser);
            $entityManager->persist($g);
        }
        $form = $this->createForm(AuthUserPermissionType::class, $authUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($authUser->getGroups() as $g) {
                $g->addUser($authUser);
                $entityManager->persist($g);
            }
            $entityManager->flush();

            return $this->redirectToRoute('auth_permission_liste', ['id' => $authUser->getid(), 'entity' => 'user']);
        }

        return $this->render('auth_user/permission.html.twig', [
            'auth_user' => $authUser,
            'form' => $form->createView(),
            'tabs' => $this->getTabsUser($authUser, "profiles", array()),
        ]);
    }
}
