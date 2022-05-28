<?php

namespace App\Controller;

use App\Entity\AuthUser;
use App\Form\AuthUserType;
use App\Repository\AuthUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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

    #[Route('/{id}', name: 'utilisateur_show', methods: ['GET'])]
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
        ]);
    }

    #[Route('/{id}', name: 'utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, AuthUser $authUser, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $authUser->getId(), $request->request->get('_token'))) {
            $entityManager->remove($authUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/dashboard', name: 'dashboard', methods: ['GET'])]
    public function dashboard(): Response
    {
        return new Response('utilisateur/show.html.twig');
    }

    #[Route('/reset-password', name: 'user_pwdreset', methods: ['GET'])]
    public function resetpwd(AuthUser $authUser): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'auth_user' => $authUser,
        ]);
    }
}
