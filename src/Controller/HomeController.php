<?php

namespace App\Controller;

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
    public function index(ProjetsRepository $projetsRepository, CompetencesRepository $competencesRepository): Response
    {
        $projets = $projetsRepository->findLastSix();
        $competences = $competencesRepository->findByCategory();
        
        return $this->render('home/index.html.twig', [
            'projets' => $projets,
            'competences' => $competences,
        ]);
    }
}
