<?php

namespace App\Controller;

use App\Entity\UniteRelation;
use App\Form\UniteRelationType;
use App\Repository\UniteRelationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;

#[Route('/unite/relation')]
class UniteRelationController extends AbstractController
{
    use TraitCommonMethods;


    #[Route('/{id}', name: 'app_unite_relation_index', methods: ['GET'])]
    public function index(UniteRelationRepository $uniteRelationRepository, Produit $produit): Response
    {
        return $this->render('unite_relation/index.html.twig', [
            'unite_relations' => $produit->getUniteRelations(),
            'produit' => $produit,
            'tabs' => $this->getTabsPrix($produit, "relations unités", []),
        ]);
    }

    #[Route('/ajout/{id}', name: 'app_unite_relation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UniteRelationRepository $uniteRelationRepository, Produit $produit): Response
    {
        $uniteRelation = new UniteRelation();
        $uniteRelation->setProduit($produit);
        $form = $this->createForm(UniteRelationType::class, $uniteRelation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uniteRelationRepository->add($uniteRelation, true);

            return $this->redirectToRoute('app_unite_relation_index', ['id'=>$produit->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('unite_relation/new.html.twig', [
            'unite_relation' => $uniteRelation,
            'form' => $form,
            'produit' => $produit,
            'tabs' => $this->getTabsPrix($produit, "relations unités", []),
        ]);
    }

    #[Route('/{id}', name: 'app_unite_relation_show', methods: ['GET'])]
    public function show(UniteRelation $uniteRelation): Response
    {
        return $this->render('unite_relation/show.html.twig', [
            'unite_relation' => $uniteRelation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_unite_relation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UniteRelation $uniteRelation, UniteRelationRepository $uniteRelationRepository): Response
    {
        $produit = $uniteRelation->getProduit();
        $form = $this->createForm(UniteRelationType::class, $uniteRelation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uniteRelationRepository->add($uniteRelation, true);

            return $this->redirectToRoute('app_unite_relation_index', ['id'=>$produit->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('unite_relation/edit.html.twig', [
            'unite_relation' => $uniteRelation,
            'form' => $form,
            'produit' => $produit,
            'tabs' => $this->getTabsPrix($produit, "relations unités", []),
        ]);
    }

    #[Route('/{id}', name: 'app_unite_relation_delete', methods: ['POST'])]
    public function delete(Request $request, UniteRelation $uniteRelation, UniteRelationRepository $uniteRelationRepository): Response
    {
        $produit = $uniteRelation->getProduit();
        if ($this->isCsrfTokenValid('delete'.$uniteRelation->getId(), $request->request->get('_token'))) {
            $uniteRelationRepository->remove($uniteRelation, true);
        }

        return $this->redirectToRoute('app_unite_relation_index', ['id'=>$produit->getId()], Response::HTTP_SEE_OTHER);
    }
}
