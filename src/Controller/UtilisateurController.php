<?php

namespace App\Controller;

use App\Entity\AuthUser;
use App\Form\AuthUserType;
use App\Repository\AuthUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\ResetPasswordForm;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[Route('/utilisateur')]
class UtilisateurController extends AbstractController
{
    #[Route('/', name: 'utilisateur_index', methods: ['GET'])]
    public function index(AuthUserRepository $authUserRepository): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'auth_users' => $authUserRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder): Response
    {
        $authUser = new AuthUser();
        $form = $this->createForm(AuthUserType::class, $authUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $authUser->setPassword(
                $passwordEncoder->hashPassword(
                    $authUser,
                    $this->getParameter("default_password")
                )
            );
            $entityManager->persist($authUser);
            $entityManager->flush();

            return $this->redirectToRoute('utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/new.html.twig', [
            'auth_user' => $authUser,
            'form' => $form,
        ]);
    }

    #[Route('/{id</d+>}', name: 'utilisateur_show', methods: ['GET'], requirements: ["id" => "\d+"])]
    public function show(AuthUser $authUser): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'auth_user' => $authUser,
        ]);
    }

    #[Route('/{id}/edit', name: 'utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AuthUser $authUser, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AuthUserType::class, $authUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/edit.html.twig', [
            'auth_user' => $authUser,
            'form' => $form,
            'editProfile' => false
        ]);
    }

    #[Route('/{id<\d+>}', name: 'utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, AuthUser $authUser, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $authUser->getId(), $request->request->get('_token'))) {
            $entityManager->remove($authUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/reset-password', name: 'user_pwdreset', methods: ['GET', 'POST'])]
    public function resetPassword(
        Request $request,
        UserPasswordHasherInterface $passwordEncoder,
        EntityManagerInterface $entityManager
    ): Response {
        $authUser = $this->getUser();
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
            $entityManager->flush();

            return $this->redirectToRoute('utilisateur_edit', ['id' => $authUser->getId()]);
        }

        return $this->render('utilisateur/resetpassword.html.twig', [
            'auth_users' => $authUser,
            'form' => $form->createView(),
            'tabs' => $this->getTabsUser($authUser, "utilisateur", array()),
        ]);
    }

    public function getTabsUser($user, $activeTab, $options)
    {

        $tabs = array(
            //'info. générale' => array('link' => $this->generateUrl('utilisateur_edit', ['id' => $user->getId()])),
            //'profiles' => array('link' => $this->generateUrl('auth_permission_liste', ['id' => $user->getId(), 'entity' => 'user'])),
        );

        foreach ($tabs as $key => $tab) {
            if ($key === $activeTab)
                $tabs[$key]['active_class'] = 'active';
        }
        return $tabs;
    }


    #[Route('/edit-profile', name: 'user_profile_edit', methods: ['GET', 'POST'])]
    public function editProfile(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $authUser = $this->getUser();
        $form = $this->createForm(AuthUserType::class, $authUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/edit.html.twig', [
            'auth_user' => $authUser,
            'form' => $form,
            'editProfile' => true
        ]);
    }
    
    #[Route('/raise-error/', name: 'user_raise_error', methods: ['GET', 'POST'])]
    public function raiseError(Request $request, MailerInterface $mailer): Response{
        $email = (new Email())
            ->from('contact@herihaja.com')
            ->to('hery.imiary@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Third email test...')
            ->text('Is this working or not!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);
        //throw new Exception("It's bad");
        return new Response("Ok, everything is fine now");
    }

    
}
