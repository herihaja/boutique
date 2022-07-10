<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ProduitService;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    use TraitCommonMethods;

    #[Route('/', name: 'produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitRepository $produitRepository): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepository->add($produit, true);

            return $this->redirectToRoute('produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepository->add($produit, true);

            return $this->redirectToRoute('produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
            'tabs' => $this->getTabsPrix($produit, "info. générale", array()),
        ]);
    }

    #[Route('/{id<\d+>}', name: 'produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit, true);
        }

        return $this->redirectToRoute('produit_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/list/{caisse}', name: 'produit_list', methods: ['GET'], defaults:['caisse'=>false])]
    public function list(ProduitRepository $produitRepository, Request $request): Response
    {
        
        if ($request->get('caisse'))
            $produits = $produitRepository->getAllWithPrice();
        else
            $produits = $produitRepository->getAllWithoutPrice();

        return new JsonResponse($produits);
    }

    #[Route('/stock', name: 'produit_stock', methods: ['GET'])]
    public function stock(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/stock.html.twig', [
            'produits' => $produitRepository->getAllStock(),
        ]);
    }

    #[Route('/approvisionnement', name: 'approvisionnement')]
    public function approvisionnement(Request $request, ProduitService $service): Response
    {
        if ($request->isMethod("post")) {
            $user = $this->getUser();

            $service->handleApprovisionnement($request->request, $user);
            return $this->redirectToRoute('approvisionnement');
        }

        return $this->render('produit/appro.html.twig', [
            'controller_name' => 'ApproController',
        ]);
    }
}
