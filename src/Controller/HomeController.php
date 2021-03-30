<?php

namespace App\Controller;

use App\Repository\AproposRepository;
use App\Repository\ProjetsRepository;
use App\Repository\CompetencesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProjetsRepository $projetsRepository, CompetencesRepository $competencesRepository, AproposRepository $aProposRepository): Response
    {
        $projets = $projetsRepository->findAll();
        // $competences = $competencesRepository->findByCategory();
        $technos = $competencesRepository->findByCategory('technologies');
        $frameworks = $competencesRepository->findByCategory('frameworks');
        $cms = $competencesRepository->findByCategory('cms');
        $apropos = $aProposRepository->findAll();
        
        return $this->render('home/index.html.twig', [
            'projets' => $projets,
            'technologies' => $technos,
            'frameworks' => $frameworks,
            'cms' => $cms,
            'apropos' => $apropos,
        ]);
    }
}
