<?php

namespace App\Controller;

use App\Entity\Produs;
use App\Form\ProdusType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Intrare;
use App\Form\IntrareType;
use App\Entity\Iesire;
use App\Form\IesireType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Knp\Component\Pager\PaginatorInterface;

class ProdusController extends AbstractController
{
    #[Route('/produse', name: 'app_produse')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator, SessionInterface $session): Response
{
    $filter = $request->query->get('filter', '');
    $search = $request->query->get('search', '');
    $page = $request->query->getInt('page', 1);  // Capture the current page

    // Store the current page in the session
    $session->set('product_list_page', $page);

    $repository = $entityManager->getRepository(Produs::class);
    $queryBuilder = $repository->createQueryBuilder('p');

    // Apply search filter
    if ($search) {
        $queryBuilder->andWhere('p.nume LIKE :search')
                     ->setParameter('search', '%' . $search . '%');
    }

    // Apply availability filter
    if ($filter === 'deleted') {
        $queryBuilder->andWhere('p.available = false');
    } elseif ($filter === 'available') {
        $queryBuilder->andWhere('p.available = true');
    }

    // Paginate the results of the query
    $pagination = $paginator->paginate(
        $queryBuilder->getQuery(), 
        $page,  // Pass the current page to pagination
        30
    );

    return $this->render('produse/produse.html.twig', [
        'pagination' => $pagination,
        'filter' => $filter,
        'search' => $search,
    ]);
}


    
#[Route('/adaugare-produs', name: 'app_add_product')]
public function addProduct(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
{
    $produs = new Produs();
    $form = $this->createForm(ProdusType::class, $produs);

    $form->handleRequest($request);

    // Retrieve the current page from the session
    $back_page = $session->get('product_list_page', 1);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($produs);
        $entityManager->flush();

        return $this->redirectToRoute('app_produse', ['page' => $back_page]);
    }

    return $this->render('produse/add.html.twig', [
        'form' => $form->createView(),
        'back_page' => $back_page // Pass back_page to the view for the "Back" button
    ]);
}


    #[Route('/produs/{id}/intrari', name: 'app_product_in')]
public function productIn(int $id, Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator, SessionInterface $session): Response
{
    $produs = $entityManager->getRepository(Produs::class)->find($id);
    if (!$produs) {
        throw $this->createNotFoundException('Produsul nu a fost găsit.');
    }

    // Retrieve the current page from the session (set when navigating from product list)
    $page = $session->get('product_list_page', 1);

    $queryBuilder = $entityManager->getRepository(Intrare::class)
        ->createQueryBuilder('i')
        ->where('i.produs = :produs')
        ->setParameter('produs', $produs);

    // Paginate the entries
    $pagination = $paginator->paginate(
        $queryBuilder->getQuery(),
        1,  // Always start at page 1 for intrari
        30
    );

    return $this->render('produse/intrari.html.twig', [
        'produs' => $produs,
        'pagination' => $pagination,
        'back_page' => $page  // Pass the page to the view for the back button
    ]);
}

#[Route('/produs/{id}/iesiri', name: 'app_product_out')]
public function productOut(int $id, Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator, SessionInterface $session): Response
{
    $produs = $entityManager->getRepository(Produs::class)->find($id);
    if (!$produs) {
        throw $this->createNotFoundException('Produsul nu a fost găsit.');
    }

    // Retrieve the current page from the session (set when navigating from product list)
    $page = $session->get('product_list_page', 1);

    $queryBuilder = $entityManager->getRepository(Iesire::class)
        ->createQueryBuilder('e')
        ->where('e.produs = :produs')
        ->setParameter('produs', $produs);

    $pagination = $paginator->paginate(
        $queryBuilder->getQuery(),
        1,  // Always start at page 1 for iesiri
        30
    );

    return $this->render('produse/iesiri.html.twig', [
        'produs' => $produs,
        'pagination' => $pagination,
        'back_page' => $page  // Pass the page to the view for the back button
    ]);
}
#[Route('/produse/edit/{id}', name: 'app_edit_product')]
public function editProduct(int $id, Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
{
    $produs = $entityManager->getRepository(Produs::class)->find($id);

    if (!$produs) {
        throw $this->createNotFoundException('Produsul nu a fost găsit.');
    }

    $form = $this->createForm(ProdusType::class, $produs);

    $form->handleRequest($request);

    // Retrieve the current page from the session
    $back_page = $session->get('product_list_page', 1);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('app_produse', ['page' => $back_page]);
    }

    return $this->render('produse/edit.html.twig', [
        'form' => $form->createView(),
        'produs' => $produs,
        'back_page' => $back_page // Pass back_page to the view for the "Back" button
    ]);
}

#[Route('/produse/delete/{id}', name: 'app_delete_product')]
public function deleteProduct(int $id, Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
{
    $produs = $entityManager->getRepository(Produs::class)->find($id);

    if (!$produs) {
        throw $this->createNotFoundException('Produsul nu a fost găsit.');
    }

    // Retrieve the current page from the session
    $back_page = $session->get('product_list_page', 1);

    if ($request->getMethod() === 'POST') {
        $produs->setAvailable(false);
        $entityManager->flush();

        return $this->redirectToRoute('app_produse', ['page' => $back_page]);
    }

    return $this->render('produse/delete.html.twig', [
        'produs' => $produs,
        'back_page' => $back_page // Pass back_page to the view for the "Back" button
    ]);
}


#[Route('/produs/{id}/intrare/add', name: 'app_add_intrare')]
public function addIntrare(int $id, Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
{
    $produs = $entityManager->getRepository(Produs::class)->find($id);
    if (!$produs) {
        throw $this->createNotFoundException('Produsul nu a fost găsit.');
    }

    $intrare = new Intrare();
    $intrare->setProdus($produs);

    $form = $this->createForm(IntrareType::class, $intrare);
    $form->handleRequest($request);

    // Retrieve the current page from the session
    $back_page = $session->get('product_list_page', 1);

    if ($form->isSubmitted() && $form->isValid()) {
        if ($intrare->getIntrari() < $intrare->getNefolosibile()) {
            $this->addFlash('error', 'Numărul de intrări nu poate fi mai mic decât numărul de articole nefolosibile.');
        } else {
            $usableIntrari = $intrare->getIntrari() - $intrare->getNefolosibile();

            // Update the product's stock after entry
            $newStock = $produs->getStoc() + $usableIntrari;
            $intrare->setStocIntrare($newStock);
            $produs->setStoc($newStock);

            $entityManager->persist($intrare);
            $entityManager->flush();

            return $this->redirectToRoute('app_product_in', ['id' => $produs->getId(), 'page' => $back_page]);
        }
    }

    return $this->render('produse/intrare_add.html.twig', [
        'form' => $form->createView(),
        'produs' => $produs,
        'back_page' => $back_page // Pass back_page to the view for the "Back" button
    ]);
}


    
#[Route('/intrare/{id}/edit', name: 'app_edit_intrare')]
public function editIntrare(int $id, Request $request, EntityManagerInterface $entityManager): Response
{
    $intrare = $entityManager->getRepository(Intrare::class)->find($id);

    if (!$intrare) {
        throw $this->createNotFoundException('Intrarea nu a fost găsită.');
    }

    // Stoc inițial al produsului înainte de modificare
    $produs = $intrare->getProdus();
    $stocInitial = $produs->getStoc();
    
    // Valorile inițiale ale intrării
    $intrariInitiale = $intrare->getIntrari();
    $nefolosibileInitiale = $intrare->getNefolosibile();
    $stocActualDeLaIntrare = $intrariInitiale - $nefolosibileInitiale;

    $form = $this->createForm(IntrareType::class, $intrare);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        if ($intrare->getIntrari() < $intrare->getNefolosibile()) {
            $this->addFlash('error', 'Numărul de intrări nu poate fi mai mic decât numărul de articole nefolosibile.');
        } 
        else{
        // Valorile actualizate
        $intrariNoi = $intrare->getIntrari();
        $nefolosibileNoi = $intrare->getNefolosibile();
        $stocNouDeLaIntrare = $intrariNoi - $nefolosibileNoi;

        // Ajustarea stocului produsului
        $diferentaStoc = $stocNouDeLaIntrare - $stocActualDeLaIntrare;
        $produs->setStoc($stocInitial + $diferentaStoc);

        // Actualizarea stocului după intrare pentru intrarea editată
        $intrare->setStocIntrare($produs->getStoc());

        $entityManager->flush();

        return $this->redirectToRoute('app_product_in', ['id' => $produs->getId()]);
    }}

    return $this->render('produse/intrare_edit.html.twig', [
        'form' => $form->createView(),
        'intrare' => $intrare,
    ]);
}

#[Route('/intrare/{id}/delete', name: 'app_delete_intrare')]
public function deleteIntrare(int $id, Request $request, EntityManagerInterface $entityManager): Response
{
    $intrare = $entityManager->getRepository(Intrare::class)->find($id);

    if (!$intrare) {
        throw $this->createNotFoundException('Intrarea nu a fost găsită.');
    }

    if ($request->getMethod() === 'POST') {
        $produs = $intrare->getProdus();
        $cantitateDeScazut = $intrare->getIntrari() - $intrare->getNefolosibile();
        $produs->setStoc($produs->getStoc() - $cantitateDeScazut);

        $entityManager->remove($intrare);
        $entityManager->flush();

        return $this->redirectToRoute('app_product_in', ['id' => $produs->getId()]);
    }

    return $this->render('produse/delete_intrare.html.twig', [
        'intrare' => $intrare,
    ]);
}



    #[Route('/produs/{id}/iesire/add', name: 'app_add_iesire')]
public function addIesire(int $id, Request $request, EntityManagerInterface $entityManager): Response
{
    $produs = $entityManager->getRepository(Produs::class)->find($id);

    if (!$produs) {
        throw $this->createNotFoundException('Produsul nu a fost găsit.');
    }

    $iesire = new Iesire();
    $iesire->setProdus($produs);

    $form = $this->createForm(IesireType::class, $iesire);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        if($iesire->getIesiri()<0){
            $this->addFlash('error', 'Numărul de ieșiri nu poate fi negativ');
        }
        elseif ($iesire->getIesiri() > $produs->getStoc()) {
            $this->addFlash('error', 'Numărul de ieșiri nu poate fi mai mare decât stocul disponibil.');
        } else {
            // Subtract the number of iesiri from the current product stock
            $newStock = $produs->getStoc() - $iesire->getIesiri();

            // Set the calculated stock after exit in the Iesire entity
            $iesire->setStocIesire($newStock);

            // Update the product's stock after exit
            $produs->setStoc($newStock);

            $entityManager->persist($iesire);
            $entityManager->flush();

            return $this->redirectToRoute('app_product_out', ['id' => $produs->getId()]);
        }
    }

    return $this->render('produse/iesire_add.html.twig', [
        'form' => $form->createView(),
        'produs' => $produs,
    ]);
}
#[Route('/iesire/{id}/edit', name: 'app_edit_iesire')]
public function editIesire(int $id, Request $request, EntityManagerInterface $entityManager): Response
{
    $iesire = $entityManager->getRepository(Iesire::class)->find($id);

    if (!$iesire) {
        throw $this->createNotFoundException('Ieșirea nu a fost găsită.');
    }

    // Stoc inițial al produsului înainte de modificare
    $produs = $iesire->getProdus();
    $stocInitial = $produs->getStoc();
    
    // Valorile inițiale ale ieșirii
    $iesiriInitiale = $iesire->getIesiri();

    $form = $this->createForm(IesireType::class, $iesire);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        // Valorile actualizate
        $iesiriNoi = $iesire->getIesiri();

        // Verificarea dacă numărul de ieșiri este negativ sau mai mare decât stocul curent
        if ($iesiriNoi < 0) {
            $this->addFlash('error', 'Numărul de ieșiri nu poate fi negativ.');
        } elseif ($iesiriNoi > $stocInitial + $iesiriInitiale) {
            $this->addFlash('error', 'Numărul de ieșiri nu poate fi mai mare decât stocul curent.');
        } else {
            // Ajustarea stocului produsului
            $diferentaStoc = $iesiriInitiale - $iesiriNoi;
            $stocNou = $stocInitial + $diferentaStoc;
            $produs->setStoc($stocNou);

            // Actualizarea stocului după ieșire pentru ieșirea editată
            $iesire->setStocIesire($stocNou);

            $entityManager->flush();

            return $this->redirectToRoute('app_product_out', ['id' => $produs->getId()]);
        }
    }

    return $this->render('produse/iesire_edit.html.twig', [
        'form' => $form->createView(),
        'iesire' => $iesire,
    ]);
}


#[Route('/iesire/{id}/delete', name: 'app_delete_iesire')]
public function deleteIesire(int $id, Request $request, EntityManagerInterface $entityManager): Response
{
    $iesire = $entityManager->getRepository(Iesire::class)->find($id);

    if (!$iesire) {
        throw $this->createNotFoundException('Ieșirea nu a fost găsită.');
    }

    if ($request->getMethod() === 'POST') {
        $produs = $iesire->getProdus();
        $cantitateDeAdaugat = $iesire->getIesiri();
        $produs->setStoc($produs->getStoc() + $cantitateDeAdaugat);

        $entityManager->remove($iesire);
        $entityManager->flush();

        return $this->redirectToRoute('app_product_out', ['id' => $produs->getId()]);
    }

    return $this->render('produse/delete_iesire.html.twig', [
        'iesire' => $iesire,
    ]);
}

#[Route('/iesire/{id}/delete/confirmation', name: 'app_delete_iesire_confirmation')]
public function deleteIesireConfirmation(int $id, EntityManagerInterface $entityManager): Response
{
    $iesire = $entityManager->getRepository(Iesire::class)->find($id);
    return $this->render('produse/delete_iesire.html.twig', ['iesire' => $iesire]);
}
#[Route('/intrare/{id}/delete/confirmation', name: 'app_delete_intrare_confirmation')]
public function deleteIntrareConfirmation(int $id, EntityManagerInterface $entityManager): Response
{
    $intrare = $entityManager->getRepository(Intrare::class)->find($id);
    return $this->render('produse/delete_intrare.html.twig', ['intrare' => $intrare]);
}


}