<?php

namespace App\Controller;

use App\Repository\IesireRepository;
use App\Repository\ProdusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IesireController extends AbstractController
{
    /**
     * @Route("/iesiri/{productId}", name="iesiri_list")
     */
    public function list(int $productId, ProdusRepository $produsRepository): Response
    {
        $produs = $produsRepository->find($productId);

        if (!$produs) {
            throw $this->createNotFoundException('The product does not exist');
        }

        $iesiri = $produs->getIesiri();

        return $this->render('produse/iesiri.html.twig', [
            'produs' => $produs,
            'iesiri' => $iesiri,
        ]);
    }
}
