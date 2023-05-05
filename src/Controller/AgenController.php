<?php

namespace App\Controller;

use App\Entity\Agen;
use App\Form\AgenType;
use App\Repository\AgenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/agen')]
class AgenController extends AbstractController
{
    #[Route('/', name: 'app_agen_index', methods: ['GET'])]
    public function index(AgenRepository $agenRepository): Response
    {
        return $this->render('agen/index.html.twig', [
            'agens' => $agenRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_agen_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AgenRepository $agenRepository): Response
    {
        $agen = new Agen();
        $form = $this->createForm(AgenType::class, $agen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agenRepository->save($agen, true);

            return $this->redirectToRoute('app_agen_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('agen/new.html.twig', [
            'agen' => $agen,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_agen_show', methods: ['GET'])]
    public function show(Agen $agen): Response
    {
        return $this->render('agen/show.html.twig', [
            'agen' => $agen,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_agen_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Agen $agen, AgenRepository $agenRepository): Response
    {
        $form = $this->createForm(AgenType::class, $agen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agenRepository->save($agen, true);

            return $this->redirectToRoute('app_agen_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('agen/edit.html.twig', [
            'agen' => $agen,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_agen_delete', methods: ['POST'])]
    public function delete(Request $request, Agen $agen, AgenRepository $agenRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$agen->getId(), $request->request->get('_token'))) {
            $agenRepository->remove($agen, true);
        }

        return $this->redirectToRoute('app_agen_index', [], Response::HTTP_SEE_OTHER);
    }
}
