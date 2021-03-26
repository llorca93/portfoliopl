<?php

namespace App\Controller;

use App\Repository\ProjetsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjetsController extends AbstractController
{
    /**
     * @Route("/admin/projets", name="admin_projets")
     */
    public function index(ProjetsRepository $projetsRepository): Response
    {

        $projets = $projetsRepository->findAll();

        return $this->render('projets/index.html.twig', [
            'projets' => $projets,
        ]);
    }
}
