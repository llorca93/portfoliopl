<?php

namespace App\Controller;

use App\Entity\Projets;
use App\Form\ProjetsType;
use App\Repository\ProjetsRepository;
use Symfony\Component\HttpFoundation\Request;
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

        return $this->render('admin/projets.html.twig', [
            'projets' => $projets,
        ]);
    }

    /**
     * @Route("/admin/projets/create", name="admin_projets_create")
     */
    public function createProjet(Request $request)
    {
        $projet = new Projets();
        $form = $this->createForm(ProjetsType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $infoImg = $form['image']->getData(); 
                $extensionImg = $infoImg->guessExtension(); 
                $nomImg = time() . '-1.' . $extensionImg; 
                $infoImg->move($this->getParameter('dossier_photos_projets'), $nomImg); 
                $projet->setImage($nomImg); 

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($projet);
                $manager->flush();
                $this->addFlash('success','Le projet a bien été ajouté');
            } else {
                $this->addFlash('danger', 'Une erreur est survenue lors de l\'ajout du projet');
            }
        }

        return $this->render('admin/projetForm.html.twig', [
            'projetForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/projets/update-{id}", name="admin_projets_update")
     */
    public function updateProjet(ProjetsRepository $projetsRepository, $id, Request $request)
    {
        $projet = $projetsRepository->find($id);
        $form = $this->createForm(ProjetsType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $infoImg = $form['image']->getData();
            $nomOldImg = $projet->getImage();
            if ($infoImg !== null) {
                
                $cheminImg = $this->getParameter('dossier_photos_maisons') . '/' . $nomOldImg;
                
                if (file_exists($cheminImg)) {
                    unlink($cheminImg);
                }

                $extensionImg = $infoImg->guessExtension(); // recupere l'extension de l'image
                $nomImg = time() . '-1.' . $extensionImg; // cree un nom unique pour l'image
                $infoImg->move($this->getParameter('dossier_photos_maisons'), $nomImg); // deplacer l'image dans le dossier adequat
                $projet->setImage($nomImg); // definit le nom de l'image a mettre en bdd


            } else {
                $projet->setImage($nomOldImg);
            }

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($projet);
            $manager->flush();
            $this->addFlash('success','Le projet a bien été modifié');
            return $this->redirectToRoute('admin_projets');
        }

        return $this->render('admin/projetForm.html.twig', [
            'projetForm' => $form->createView(),
        ]);
        
    }

    /**
     * @Route("admin/projets/delete-{id}", name="admin_projets_delete")
     */
    public function deleteProjet(ProjetsRepository $projetsRepository, $id) 
    {
        $projet = $projetsRepository->find($id);

        $nomImg = $projet->getImage();
        if ($nomImg !== null) {
            $cheminImg = $this->getParameter('dossier_photos_projets') . '/' . $nomImg;
            if (file_exists($cheminImg)) {
                unlink($cheminImg);
            }
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($projet);
        $manager->flush();
        $this->addFlash('success', 'Le projet a bien été supprimé');

        return $this->redirectToRoute('admin_projets');
    }

    
}
