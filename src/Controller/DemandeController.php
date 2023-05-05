<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Form\DemandeType;
use App\Repository\AgenRepository;
use App\Repository\DemandeRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/demande')]
class DemandeController extends AbstractController
{
    #[Route('/', name: 'app_demande_index', methods: ['GET'])]
    public function index(DemandeRepository $demandeRepository): Response
    {
        return $this->render('demande/index.html.twig', [
            'demandes' => $demandeRepository->findAll(),
        ]);
    }
#[Route('/me', name: 'app_demande_me', methods: ['GET'])]
    public function me(DemandeRepository $demandeRepository,AgenRepository $agenRepository): Response
    {
        $id = $this->getUser()->getId();

        return $this->render('demande/index.html.twig', [
            'demandes' => $demandeRepository->findBy(['agen'=>$agenRepository->find($id)]),
        ]);
    }

    #[Route('/new', name: 'app_demande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DemandeRepository $demandeRepository,AgenRepository $agenRepository): Response
    {
        $demande = new Demande();
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);
        $id = $this->getUser()->getId();


        $demande->setAgen($agenRepository->find($id));
        $demande->setState(false);
        $date = new DateTime();
        $demande->setDate($date);
        if ($form->isSubmitted() && $form->isValid()) {
            $demandeRepository->save($demande, true);

            return $this->redirectToRoute('app_Menu', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('demande/new.html.twig', [
            'demande' => $demande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demande_show', methods: ['GET'])]
    public function show(Demande $demande): Response
    {
        return $this->render('demande/show.html.twig', [
            'demande' => $demande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_demande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Demande $demande, DemandeRepository $demandeRepository): Response
    {
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demandeRepository->save($demande, true);

            return $this->redirectToRoute('app_demande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('demande/edit.html.twig', [
            'demande' => $demande,
            'form' => $form,
        ]);
    }
 #[Route('/{id}/autoriser', name: 'app_demande_autoriser', methods: ['GET', 'POST'])]
    public function autoriser(Request $request, Demande $demande, DemandeRepository $demandeRepository): Response
    {
        $demande->setState(true);
            $demandeRepository->save($demande, true);

            return $this->redirectToRoute('app_demande_index', [], Response::HTTP_SEE_OTHER);

    }
#[Route('/{id}/refuser', name: 'app_demande_refuser', methods: ['GET', 'POST'])]
    public function refuser(Request $request, Demande $demande, DemandeRepository $demandeRepository): Response
    {
        $demande->setState(false);
            $demandeRepository->save($demande, true);

            return $this->redirectToRoute('app_demande_index', [], Response::HTTP_SEE_OTHER);

    }

    #[Route('/{id}', name: 'app_demande_delete', methods: ['POST'])]
    public function delete(Request $request, Demande $demande, DemandeRepository $demandeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demande->getId(), $request->request->get('_token'))) {
            $demandeRepository->remove($demande, true);
        }

        return $this->redirectToRoute('app_demande_index', [], Response::HTTP_SEE_OTHER);
    }
}
