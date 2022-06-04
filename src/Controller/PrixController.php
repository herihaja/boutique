<?php

namespace App\Controller;

use App\Entity\Prix;
use App\Entity\Produit;
use App\Form\PrixType;
use App\Repository\PrixRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/prix')]
class PrixController extends AbstractController
{
    use TraitCommonMethods;

    #[Route('/{id<\d+>}/produit', name: 'prix_index', methods: ['GET'])]
    public function index(PrixRepository $prixRepository, Produit $produit): Response
    {
        return $this->render('prix/index.html.twig', [
            'prixes' => $produit->getPrix(),
            'produit' => $produit,
            'tabs' => $this->getTabsPrix($produit, "prix", array()),
        ]);
    }

    #[Route('/ajout/{id<\d+>}', name: 'prix_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PrixRepository $prixRepository, Produit $produit): Response
    {
        $prix = new Prix();
        $prix->setProduit($produit);
        $form = $this->createForm(PrixType::class, $prix);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $prixRepository->add($prix, true);

            return $this->redirectToRoute('prix_index', ['id' => $produit->getId(), 'entity' => 'produit'], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('prix/new.html.twig', [
            'prix' => $prix,
            'form' => $form,
            'produit' => $produit,
            'tabs' => $this->getTabsPrix($produit, "prix", array()),
        ]);
    }

    #[Route('/{id<\d+>}', name: 'prix_show', methods: ['GET'])]
    public function show(Prix $prix): Response
    {
        return $this->render('prix/show.html.twig', [
            'prix' => $prix,
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'prix_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Prix $prix, PrixRepository $prixRepository): Response
    {
        $form = $this->createForm(PrixType::class, $prix);
        $form->handleRequest($request);
        $produit = $prix->getProduit();

        if ($form->isSubmitted() && $form->isValid()) {
            $prixRepository->add($prix, true);

            return $this->redirectToRoute('prix_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('prix/edit.html.twig', [
            'prix' => $prix,
            'form' => $form,
            'produit' => $produit,
            'tabs' => $this->getTabsPrix($produit, "prix", array()),
        ]);
    }

    #[Route('/{id}', name: 'prix_delete', methods: ['POST'])]
    public function delete(Request $request, Prix $prix, PrixRepository $prixRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $prix->getId(), $request->request->get('_token'))) {
            $prixRepository->remove($prix, true);
        }

        return $this->redirectToRoute('prix_index', [], Response::HTTP_SEE_OTHER);
    }
}
