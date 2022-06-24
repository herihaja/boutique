<?php

namespace App\Controller;

use App\Entity\ParametreValeur;
use App\Entity\Parametre;
use App\Form\ParametreValeurType;
use App\Repository\ParametreValeurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/parametre-valeur')]
class ParametreValeurController extends AbstractController
{
    #[Route('/{id<\d+>}', name: 'parametre_valeur_index', methods: ['GET'])]
    public function index(ParametreValeurRepository $parametreValeurRepository, Request $request
        , EntityManagerInterface $entityManager): Response
    {
        $parametre = $request->get("id");
        return $this->render('parametre_valeur/index.html.twig', [
            'parametre_valeurs' => $parametreValeurRepository->findByParametre($parametre),
            'parametre' => $entityManager->getReference(Parametre::class, $parametre),
        ]);
    }

    #[Route('/ajout/{id<\d+>}', name: 'parametre_valeur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ParametreValeurRepository $parametreValeurRepository,
         EntityManagerInterface $entityManager): Response
    {
        $parametreId = $request->get("id");
        $parametre = $entityManager->getReference(Parametre::class, $parametreId);
        $parametreValeur = new ParametreValeur();
        $parametreValeur->setParametre($parametre);
        
        $form = $this->createForm(ParametreValeurType::class, $parametreValeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $parametreValeurRepository->add($parametreValeur, true);

            return $this->redirectToRoute('parametre_valeur_index', ['id'=> $parametreId], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('parametre_valeur/new.html.twig', [
            'parametre_valeur' => $parametreValeur,
            'form' => $form,
            'parametre' => $parametre,
        ]);
    }

    #[Route('/{id}', name: 'parametre_valeur_show', methods: ['GET'])]
    public function show(ParametreValeur $parametreValeur): Response
    {
        $parametre = $parametreValeur->getParametre();
        return $this->render('parametre_valeur/show.html.twig', [
            'parametre_valeur' => $parametreValeur,
            'parametre' => $parametre,
        ]);
    }

    #[Route('/{id}/edit', name: 'parametre_valeur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ParametreValeur $parametreValeur, ParametreValeurRepository $parametreValeurRepository): Response
    {
        $parametre = $parametreValeur->getParametre();
        $form = $this->createForm(ParametreValeurType::class, $parametreValeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $parametreValeurRepository->add($parametreValeur, true);

            return $this->redirectToRoute('parametre_valeur_index', ['id'=> $parametre->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('parametre_valeur/edit.html.twig', [
            'parametre_valeur' => $parametreValeur,
            'form' => $form,
            'parametre' => $parametre,
        ]);
    }

    #[Route('/{id}', name: 'parametre_valeur_delete', methods: ['POST'])]
    public function delete(Request $request, ParametreValeur $parametreValeur, ParametreValeurRepository $parametreValeurRepository): Response
    {
        $parametre = $parametreValeur->getParametre();
        if ($this->isCsrfTokenValid('delete' . $parametreValeur->getId(), $request->request->get('_token'))) {
            $parametreValeurRepository->remove($parametreValeur, true);
        }

        return $this->redirectToRoute('parametre_valeur_index', ['id'=> $parametre->getId()], Response::HTTP_SEE_OTHER);
    }
}
