<?php

namespace App\Controller;

use App\Repository\ProdusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IntrareController extends AbstractController
{
    /**
     * @Route("/intrari/{productId}", name="intrari_list")
     */
    public function list(int $productId, ProdusRepository $produsRepository): Response
    {
        $produs = $produsRepository->find($productId);

        if (!$produs) {
            throw $this->createNotFoundException('The product does not exist');
        }

        $intrari = $produs->getIntrari();

        // Convert to array for debugging purposes
        $intrariArray = $intrari->toArray();

        // Debugging to ensure intrariArray is correctly populated
        dump($intrariArray);

        return $this->render('produse/intrari.html.twig', [
            'produs' => $produs,
            'intrari' => $intrariArray,
        ]);
    }
}
