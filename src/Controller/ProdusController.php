<?php

namespace App\Controller;

use App\Entity\Produs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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
}
