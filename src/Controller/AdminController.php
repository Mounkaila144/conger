<?php

namespace App\Controller;

use App\Repository\AgenRepository;
use App\Repository\DemandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('Menu/', name: 'app_Menu')]
    public function index(AgenRepository $agenRepository, DemandeRepository $demandeRepository): Response
    {
        $id = $this->getUser()->getId();
        $totalAgens = $agenRepository->count([]);
        $totalDemande = $demandeRepository->count([]);
        $totalDemandeme = $demandeRepository->count(['agen'=>$agenRepository->find($id)]);
        $demandeAutoriserme = $demandeRepository->count(['state'=>true,'agen'=>$agenRepository->find($id)]);
        $demandeRefuserme = $demandeRepository->count(['state'=>false,'agen'=>$agenRepository->find($id)]);
 $demandeAutoriser = $demandeRepository->count(['state'=>true]);
        $demandeRefuser = $demandeRepository->count(['state'=>false]);

        return $this->render('admin/index.html.twig', [
            'totalAgens' => $totalAgens,
            'totalDemande' => $totalDemande,
            'totalDemandeme' => $totalDemandeme,
            'demandeAutoriser' => $demandeAutoriser,
            'demandeRefuser' => $demandeRefuser,
            'demandeAutoriserme' => $demandeAutoriserme,
            'demandeRefuserme' => $demandeRefuserme,
        ]);
    }
}
