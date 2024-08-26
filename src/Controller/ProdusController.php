<?php

namespace App\Controller;

use App\Entity\Produs;
use App\Form\ProdusType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProdusController extends AbstractController
{
    #[Route('/produse', name: 'app_produse')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]  // Ensures the user is logged in to view the products
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Fetch all products from the database
        $produse = $entityManager->getRepository(Produs::class)->findAll();

        // Render the 'produse/index.html.twig' template and pass the products to it
        return $this->render('produse/produse.html.twig', [
            'produse' => $produse,
        ]);
    }
    #[Route('/adaugare-produs', name: 'app_add_product')]
    public function addProduct(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produs = new Produs();
        $form = $this->createForm(ProdusType::class, $produs);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($produs);
            $entityManager->flush();

            return $this->redirectToRoute('app_produse');
        }

        return $this->render('produse/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/produs{id}/intrari', name: 'app_product_in')]
    public function productIn(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $produs = $entityManager->getRepository(Produs::class)->find($id);

        if (!$produs) {
            throw $this->createNotFoundException('Produsul nu a fost găsit.');
        }

        if ($request->isMethod('POST')) {
            $amount = (int)$request->request->get('amount');
            $produs->setStoc($produs->getStoc() + $amount);
            $entityManager->flush();

            return $this->redirectToRoute('app_produse');
        }

        return $this->render('produse/intrari.html.twig', [
            'produs' => $produs,
        ]);
    }

    #[Route('/produs{id}/iesiri', name: 'app_product_out')]
    public function productOut(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $produs = $entityManager->getRepository(Produs::class)->find($id);

        if (!$produs) {
            throw $this->createNotFoundException('Produsul nu a fost găsit.');
        }

        if ($request->isMethod('POST')) {
            $amount = (int)$request->request->get('amount');
            $produs->setStoc($produs->getStoc() - $amount);
            $entityManager->flush();

            return $this->redirectToRoute('app_produse');
        }

        return $this->render('produse/iesiri.html.twig', [
            'produs' => $produs,
        ]);
    }
}