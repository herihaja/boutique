<?php

namespace App\Controller;

use App\Entity\ParametreValeur;
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
    #[Route('/', name: 'parametre_valeur_index', methods: ['GET'])]
    public function index(ParametreValeurRepository $parametreValeurRepository): Response
    {
        return $this->render('parametre_valeur/index.html.twig', [
            'parametre_valeurs' => $parametreValeurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'parametre_valeur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ParametreValeurRepository $parametreValeurRepository): Response
    {
        $parametreValeur = new ParametreValeur();
        $form = $this->createForm(ParametreValeurType::class, $parametreValeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $parametreValeurRepository->add($parametreValeur, true);

            return $this->redirectToRoute('parametre_valeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('parametre_valeur/new.html.twig', [
            'parametre_valeur' => $parametreValeur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'parametre_valeur_show', methods: ['GET'])]
    public function show(ParametreValeur $parametreValeur): Response
    {
        return $this->render('parametre_valeur/show.html.twig', [
            'parametre_valeur' => $parametreValeur,
        ]);
    }

    #[Route('/{id}/edit', name: 'parametre_valeur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ParametreValeur $parametreValeur, ParametreValeurRepository $parametreValeurRepository): Response
    {
        $form = $this->createForm(ParametreValeurType::class, $parametreValeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $parametreValeurRepository->add($parametreValeur, true);

            return $this->redirectToRoute('parametre_valeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('parametre_valeur/edit.html.twig', [
            'parametre_valeur' => $parametreValeur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'parametre_valeur_delete', methods: ['POST'])]
    public function delete(Request $request, ParametreValeur $parametreValeur, ParametreValeurRepository $parametreValeurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $parametreValeur->getId(), $request->request->get('_token'))) {
            $parametreValeurRepository->remove($parametreValeur, true);
        }

        return $this->redirectToRoute('parametre_valeur_index', [], Response::HTTP_SEE_OTHER);
    }
}
